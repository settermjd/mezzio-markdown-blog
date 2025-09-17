<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\Iterator;

use ArrayIterator;
use Mni\FrontYAML\Parser;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use Settermjd\MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use Settermjd\MarkdownBlog\Iterator\FilterPostByCategoryIterator;

final class FilterPostByCategoryIteratorTest extends TestCase
{
    use DataTrait;

    #[DataProvider('filterByCategoryDataProvider')]
    public function testCanFilterPostsByCategory(string $category, int $postCount): void
    {
        $this->setupArticleData();

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory()
        );
        $posts                         = new FilterPostByCategoryIterator(
            new ArrayIterator($itemLister->getArticles()),
            $category
        );
        $this->assertCount($postCount, $posts);
    }

    /**
     * @return (int|string)[][]
     * @psalm-return list{list{'Podcasts', 0}, list{'Software Development', 5}, list{'Public Speaking', 0}}
     */
    public static function filterByCategoryDataProvider(): array
    {
        return [
            [
                'Podcasts',
                0,
            ],
            [
                'Software Development',
                5,
            ],
            [
                'Public Speaking',
                0,
            ],
        ];
    }
}
