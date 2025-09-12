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

final class PublishedItemFilterIteratorTest extends TestCase
{
    use DataTrait;

    public function testCanFindPublishedPosts(): void
    {
        $this->setupArticleData();

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory(),
        );

        $articles = new PublishedItemFilterIterator(
            new ArrayIterator($itemLister->getArticles())
        );

        $this->assertCount(4, $articles);
    }
}
