<?php

declare(strict_types=1);

namespace MarkdownBlog\Items\Adapter;

use DirectoryIterator;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\InputFilter\InputFilterInterface;
use MarkdownBlog\Items\ItemListerInterface;
use MarkdownBlog\Iterator\MarkdownFileFilterIterator;
use MarkdownBlog\Entity\BlogArticle;
use Mni\FrontYAML\Document;
use Mni\FrontYAML\Parser;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Traversable;

/**
 * Class ItemListerFilesystem.
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2015 Matthew Setter
 */
class ItemListerFilesystem implements ItemListerInterface
{
    public const CACHE_KEY_EPISODES_LIST = 'episodes_';
    public const CACHE_KEY_SUFFIX_ALL = 'all';
    public const CACHE_KEY_SUFFIX_UPCOMING = 'upcoming';
    public const CACHE_KEY_SUFFIX_PAST = 'past';

    protected string $postDirectory;
    private InputFilterInterface $inputFilter;
    protected Parser $fileParser;
    protected MarkdownFileFilterIterator $episodeIterator;
    protected ?object $cache = null;
    private ?LoggerInterface $logger = null;

    public function __construct(
        string $postDirectory,
        Parser $fileParser,
        InputFilterInterface $inputFilter,
        $cache = null,
        LoggerInterface $logger = null
    ) {
        $this->postDirectory = $postDirectory;
        $this->fileParser = $fileParser;

        if (is_object($cache)) {
            $this->cache = $cache;
        }

        $this->episodeIterator = new MarkdownFileFilterIterator(
            new DirectoryIterator($this->postDirectory)
        );
        $this->inputFilter = $inputFilter;
        $this->logger = $logger;
    }

    /**
     * Return the available articles.
     *
     * @return array<int,BlogArticle>
     */
    public function getArticles($cacheKeySuffix = self::CACHE_KEY_SUFFIX_ALL): array
    {
        if ($this->cache) {
            $cacheKey = self::CACHE_KEY_EPISODES_LIST.$cacheKeySuffix;
            $result = $this->cache->getItem($cacheKey);
            if (!$result) {
                $result = $this->buildArticlesList();
                $this->cache->setItem($cacheKey, $result);
            }
            return $result;
        }

        return $this->buildArticlesList();
    }

    /**
     * @return array<int,BlogArticle>
     */
    protected function buildArticlesList(): array
    {
        $episodeListing = [];
        foreach ($this->episodeIterator as $file) {
            $article = $this->buildArticleFromFile($file);
            if (! is_null($article)) {
                $episodeListing[] = $article;
            }
        }

        return $episodeListing;
    }

    /**
     * @return BlogArticle|string
     */
    public function getArticle($episodeSlug)
    {
        foreach ($this->episodeIterator as $file) {
            $article = $this->buildArticleFromFile($file);
            if (! is_null($article) && $article->getSlug() === $episodeSlug) {
                return $article;
            }
        }

        return '';
    }

    public function buildArticleFromFile(\SplFileInfo $file): ?BlogArticle
    {
        $fileContent = file_get_contents($file->getPathname());

        /** @var Document $document */
        $document = $this->fileParser->parse($fileContent, false);
        $articleData = $this->getArticleData($document);

        $this->inputFilter->setData($articleData);
        if (! $this->inputFilter->isValid()) {
            if ($this->logger instanceof LoggerInterface) {
                $this->logger->error(
                    sprintf(
                        'Could not instantiate blog item for file %s.',
                        $file->getPathname()
                    ),
                    $this->inputFilter->getMessages()
                );
            }
            return null;
        }

        return (new ArraySerializableHydrator())
            ->hydrate(
                $this->inputFilter->getValues(),
                new BlogArticle()
            );
    }

    public function getArticleData(Document $document): array
    {
        return [
            'categories' => $document->getYAML()['categories'] ?? [],
            'content' => $document->getContent(),
            'image' => $document->getYAML()['image'] ?? '',
            'publishDate' => $document->getYAML()['publish_date'] ?? '',
            'slug' => $document->getYAML()['slug'] ?? '',
            'synopsis' => $document->getYAML()['synopsis'] ?? '',
            'tags' => $document->getYAML()['tags'] ?? [],
            'title' => $document->getYAML()['title'] ?? '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCategories(): array
    {
        $categories = [];
        $articles = $this->getArticles();
        foreach ($articles as $article) {
            if ($article instanceof BlogArticle) {
                $categories = array_merge($categories, $article->getCategories());
            }
        }

        sort($categories);
        return array_unique($categories);
    }

    /**
     * @inheritDoc
     */
    public function getTags(): array
    {
        $tags = [];
        $articles = $this->getArticles();
        foreach ($articles as $article) {
            if ($article instanceof BlogArticle) {
                $tags = array_merge($tags, $article->getTags());
            }
        }

        sort($tags);
        return array_unique($tags);
    }
}
