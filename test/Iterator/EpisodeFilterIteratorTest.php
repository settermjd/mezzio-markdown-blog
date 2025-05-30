<?php

declare(strict_types=1);

namespace MarkdownBlogTest\Iterator;

use ArrayIterator;
use MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use MarkdownBlog\Iterator\PublishedItemFilterIterator;
use MarkdownBlog\Iterator\UpcomingItemFilterIterator;
use Mni\FrontYAML\Parser;
use org\bovigo\vfs\vfsStream;
use Override;
use PHPUnit\Framework\TestCase;

use function iterator_count;
use function sprintf;

final class EpisodeFilterIteratorTest extends TestCase
{
    use DataTrait;

    #[Override]
    protected function setUp(): void
    {
        $this->setupArticleData();
    }

    public function testCanFilterUpcomingItems(): void
    {
        $this->setUp();

        vfsStream::setup('root', null, $this->structure);

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory()
        );
        $upcomingItems                 = new UpcomingItemFilterIterator(
            new ArrayIterator(
                $itemLister->getArticles()
            )
        );
        $this->assertCount(
            2,
            $upcomingItems,
            sprintf(
                "Incorrect upcoming item count retrieved. Count was %s",
                iterator_count($upcomingItems)
            )
        );
    }

    public function testCanGetAllPastItems(): void
    {
        $this->setUp();

        vfsStream::setup('root', null, $this->structure);

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory()
        );
        $publishedItems                = new PublishedItemFilterIterator(
            new ArrayIterator(
                $itemLister->getArticles()
            )
        );
        $this->assertCount(4, $publishedItems, "Incorrect past item count retrieved");
    }
}
