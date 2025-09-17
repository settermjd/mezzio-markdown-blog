<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\Iterator;

use ArrayIterator;
use Mni\FrontYAML\Parser;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use Settermjd\MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use Settermjd\MarkdownBlog\Iterator\PublishedItemFilterIterator;
use Settermjd\MarkdownBlog\Iterator\UpcomingItemFilterIterator;

use function iterator_count;
use function sprintf;

final class EpisodeFilterIteratorTest extends TestCase
{
    use DataTrait;

    public function testCanFilterUpcomingItems(): void
    {
        $this->setupArticleData();

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
        $this->setupArticleData();

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
