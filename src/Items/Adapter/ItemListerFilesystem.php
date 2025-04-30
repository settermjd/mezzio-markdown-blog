<?php

declare(strict_types=1);

namespace MarkdownBlog\Items\Adapter;

use DirectoryIterator;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\InputFilter\InputFilterInterface;
use MarkdownBlog\Entity\BlogArticle;
use MarkdownBlog\Items\ItemListerInterface;
use MarkdownBlog\Iterator\MarkdownFileFilterIterator;
use Mni\FrontYAML\Document;
use Mni\FrontYAML\Parser;
use Override;
use Psr\Log\LoggerInterface;
use SplFileInfo;

use function array_merge;
use function array_unique;
use function file_get_contents;
use function is_object;
use function sort;
use function sprintf;

final class ItemListerFilesystem implements ItemListerInterface
{
    public const CACHE_KEY_EPISODES_LIST   = 'episodes_';
    public const CACHE_KEY_SUFFIX_ALL      = 'all';
    public const CACHE_KEY_SUFFIX_UPCOMING = 'upcoming';
    public const CACHE_KEY_SUFFIX_PAST     = 'past';

    protected MarkdownFileFilterIterator $episodeIterator;
    protected object|null $cache = null;

    public function __construct(
        protected string $postDirectory,
        protected Parser $fileParser,
        private InputFilterInterface $inputFilter,
        $cache = null,
        private LoggerInterface|null $logger = null
    ) {
        $this->postDirectory = $postDirectory;
        $this->fileParser    = $fileParser;

        if (is_object($cache)) {
            $this->cache = $cache;
        }

        $this->episodeIterator = new MarkdownFileFilterIterator(
            new DirectoryIterator($this->postDirectory)
        );
        $this->inputFilter     = $inputFilter;
        $this->logger          = $logger;
    }

    /**
     * Return the available articles.
     *
     * @return array<int,BlogArticle>
     */
    #[Override]
    public function getArticles(string $cacheKeySuffix = self::CACHE_KEY_SUFFIX_ALL): array
    {
        if ($this->cache) {
            $cacheKey = self::CACHE_KEY_EPISODES_LIST . $cacheKeySuffix;
            $result   = $this->cache->getItem($cacheKey);
            if (! $result) {
                $result = $this->buildArticlesList();
                $this->cache->setItem($cacheKey, $result);
            }
            return $result;
        }

        return $this->buildArticlesList();
    }

    /**
     * @return BlogArticle[]
     */
    protected function buildArticlesList(): array
    {
        $episodeListing = [];
        foreach ($this->episodeIterator as $file) {
            $article = $this->buildArticleFromFile($file);
            if ($article !== null) {
                $episodeListing[] = $article;
            }
        }

        return $episodeListing;
    }

    public function buildArticleFromFile(SplFileInfo $file): BlogArticle|null
    {
        $fileContent = file_get_contents($file->getPathname());

        $document    = $this->fileParser->parse($fileContent, false);
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

    /**
     * @return (array|mixed|string)[]
     * @psalm-return array{categories: array<never, never>|mixed, content: string, image: ''|mixed, publishDate: ''|mixed, slug: ''|mixed, synopsis: ''|mixed, tags: array<never, never>|mixed, title: ''|mixed}
     */
    public function getArticleData(Document $document): array
    {
        return [
            'categories'  => $document->getYAML()['categories'] ?? [],
            'content'     => $document->getContent(),
            'image'       => $document->getYAML()['image'] ?? '',
            'publishDate' => $document->getYAML()['publish_date'] ?? '',
            'slug'        => $document->getYAML()['slug'] ?? '',
            'synopsis'    => $document->getYAML()['synopsis'] ?? '',
            'tags'        => $document->getYAML()['tags'] ?? [],
            'title'       => $document->getYAML()['title'] ?? '',
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCategories(): array
    {
        $categories = [];
        $articles   = $this->getArticles();
        foreach ($articles as $article) {
            $categories = array_merge($categories, $article->getCategories());
        }

        sort($categories);
        return array_unique($categories);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getTags(): array
    {
        $tags     = [];
        $articles = $this->getArticles();
        foreach ($articles as $article) {
            $tags = array_merge($tags, $article->getTags());
        }

        sort($tags);
        return array_unique($tags);
    }
}
