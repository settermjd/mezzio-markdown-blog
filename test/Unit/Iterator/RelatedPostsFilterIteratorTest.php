<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\Iterator;

use ArrayIterator;
use Mni\FrontYAML\Parser;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\Entity\BlogArticle;
use Settermjd\MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use Settermjd\MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use Settermjd\MarkdownBlog\Iterator\RelatedPostsFilterIterator;

use function array_filter;
use function iterator_to_array;

final class RelatedPostsFilterIteratorTest extends TestCase
{
    use DataTrait;

    /**
     * @param array<string,string> $blogArticleData
     * @param string[] $articleSlugs
     */
    #[DataProvider('relatedPostDataProvider')]
    public function testCanFindRelatedPostsForCurrentPost(
        array $blogArticleData,
        int $articleCount,
        array $articleSlugs
    ): void {
        $this->setupArticleData();

        $article = new BlogArticle($blogArticleData);

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory(),
        );

        $articles = new RelatedPostsFilterIterator(
            new ArrayIterator($itemLister->getArticles()),
            $article
        );

        $this->assertCount($articleCount, $articles);
        foreach ($articleSlugs as $articleSlug) {
            $this->assertCount(
                1,
                array_filter(
                    iterator_to_array($articles),
                    fn (BlogArticle $article) => $article->getSlug() === $articleSlug
                )
            );
        }
    }

    /**
     * @return ((string|string[])[]|int)[][]
     */
    public static function relatedPostDataProvider(): array
    {
        return [
            [
                [
                    "publishDate" => "2015-01-01",
                    "slug"        => "item-0003",
                    "title"       => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
                    "content"     => <<<EOF
In this blogArticle, I have a fireside chat with internationally recognized PHP expert, and all around good fella [Paul M. Jones](http://paul-m-jones.com), about one of his all-time favorite books - [The Mythical Man Month](http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959).

We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975.

I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, **and much, much more**.

> **Correction:** Thanks to [@asgrim](https://twitter.com/@asgrim) for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.
EOF,
                    "synopsis"    => 'In this blogArticle, I have a fireside chat with internationally recognized PHP expert Paul M. Jones about one of his all-time favorite books, The Mythical Man Month.',
                    "image"       => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
                    'tags'        => ['PHP', 'Docker'],
                    'categories'  => ['Software Development'],
                ],
                4,
                [
                    'item-0004',
                ],
            ],
        ];
    }
}
