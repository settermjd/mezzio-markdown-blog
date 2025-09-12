<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\Items\Adapter;

use Mni\FrontYAML\Parser;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Settermjd\MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use Settermjd\MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use Settermjd\MarkdownBlogTest\Unit\Iterator\DataTrait;

use function sprintf;

/**
 * @coversDefaultClass ItemListerFilesystem
 */
final class ItemListerFilesystemTest extends TestCase
{
    use DataTrait;

    public function testCanRetrieveASortedUniqueListOfCategories(): void
    {
        $this->setupArticleData();

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory(),
            null,
            null
        );

        $categories = $itemLister->getCategories();
        $this->assertCount(1, $categories);
        $this->assertSame(
            [
                'Software Development',
            ],
            $categories,
        );
    }

    public function testCanRetrieveASortedUniqueListOfTags(): void
    {
        $this->setupArticleData();

        vfsStream::setup('root', null, $this->structure);

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory(),
            null,
            null
        );

        $tags = $itemLister->getTags();
        $this->assertCount(7, $tags);
        $this->assertEqualsCanonicalizing(
            [
                "Containers",
                "Docker",
                "Laravel",
                "PHP",
                "Ruby",
                "Slim Framework",
                "Testing",
            ],
            $tags,
        );
    }

    public function testDataIsProperlyValidatedAndFiltered(): void
    {
        $this->markTestSkipped("Need to figure out why error is not called");

        $this->setupArticleData();

        /** @var LoggerInterface&MockObject $log */
        $log = $this->createMock(LoggerInterface::class);
        $log
            ->expects($this->once())
            ->method('error')
            ->with(
                'Could not instantiate blog item for file vfs://root/posts/item-0001.md.',
                [
                    'publishDate' => [
                        'regexNotMatch' => sprintf(
                            "The input does not match against pattern '%s'",
                            BlogArticleInputFilterFactory::FILTER_REGEX,
                        ),
                    ],
                ]
            );

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory(),
            null,
            $log
        );

        $articles = $itemLister->getArticles();
        $this->assertCount(4, $articles);
    }
}
