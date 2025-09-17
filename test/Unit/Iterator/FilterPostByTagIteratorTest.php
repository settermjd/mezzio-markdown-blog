<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\Iterator;

use ArrayIterator;
use Mni\FrontYAML\Parser;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\Entity\BlogArticle;
use Settermjd\MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use Settermjd\MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;
use Settermjd\MarkdownBlog\Iterator\FilterPostByTagIterator;
use Settermjd\MarkdownBlog\Iterator\PublishedItemFilterIterator;

final class FilterPostByTagIteratorTest extends TestCase
{
    use DataTrait;

    #[DataProvider('filterByTagDataProvider')]
    public function testCanFilterPostsByTag(string $tag, int $postCount): void
    {
        $this->setupArticleData();

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory()
        );
        $posts                         = new FilterPostByTagIterator(
            new ArrayIterator($itemLister->getArticles()),
            $tag
        );
        $this->assertCount($postCount, $posts);
    }

    /**
     * @return (int|string)[][]
     * @psalm-return list{list{'Kubernetes', 0}, list{'PHP', 3}, list{'Docker', 1}, list{'Slim Framework', 2}}
     */
    public static function filterByTagDataProvider(): array
    {
        return [
            [
                'Kubernetes',
                0,
            ],
            [
                'PHP',
                3,
            ],
            [
                'Docker',
                1,
            ],
            [
                'Slim Framework',
                2,
            ],
        ];
    }

    public function testCanHandleTagListsWithEmptyAndNullValues(): void
    {
        $item = new BlogArticle([
            "publishDate" => "2015-01-01",
            "slug"        => "blogArticle-001",
            "title"       => "BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001 BlogArticle 001",
            "content"     => <<<EOF
In this blogArticle, I have a fireside chat with internationally recognized PHP expert, and all around good fella [Paul M. Jones](http://paul-m-jones.com), about one of his all-time favorite books - [The Mythical Man Month](http://www.amazon.co.uk/The-Mythical-Man-month-Software-Engineering/dp/0201835959).

We talk about why the book is so valuable to him, how it's helped shape his career over the years, and the lessons it can teach all of us as software developers, lessons still relevant over 50 years after it was first published, in 1975.

I've also got updates on what's been happening for me personally in my freelancing business; including speaking at php[world], attending the inaugural PHP South Coast, **and much, much more**.

> **Correction:** Thanks to [@asgrim](https://twitter.com/@asgrim) for correcting me about employers rarely, if ever, paying for flights and hotels when sending staff to conferences. That was a slip up on my part. I'd only meant to say that they cover the costs of the ticket.
EOF,
            "synopsis"    => 'In this blogArticle, I have a fireside chat with internationally recognized PHP expert Paul M. Jones about one of his all-time favorite books, The Mythical Man Month.',
            "image"       => "http://traffic.libsyn.com/thegeekyfreelancer/FreeTheGeek-Episode0002.mp3",
            'tags'        => [null, ''],
            'categories'  => ['Software Development'],
        ]);

        /** @var ItemListerInterface&MockObject itemLister */
        $itemLister = $this->createMock(ItemListerInterface::class);
        $itemLister
            ->expects($this->once())
            ->method('getArticles')
            ->willReturn([
                $item,
            ]);

        $posts = new FilterPostByTagIterator(
            new PublishedItemFilterIterator(
                new ArrayIterator($itemLister->getArticles())
            ),
            'PHP'
        );
        $this->assertCount(0, $posts);
    }
}
