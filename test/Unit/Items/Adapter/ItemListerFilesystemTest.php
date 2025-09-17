<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\Items\Adapter;

use Mni\FrontYAML\Parser;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use Settermjd\MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use Settermjd\MarkdownBlogTest\Unit\Iterator\DataTrait;

use function array_values;

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
        $this->assertCount(2, $categories);
        $this->assertSame(
            [
                'Software Development',
                'Technical Writing',
            ],
            array_values($categories),
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
        $this->assertCount(8, $tags);
        $this->assertEqualsCanonicalizing(
            [
                "Containers",
                "Developer Education",
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

    #[TestWith(['item-0005', 0])]
    #[TestWith(['item-0001', 3])]
    public function testCanRetrieveRelatedArticles(string $slug, int $expectedArticles): void
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

        $articles = $itemLister->getRelatedArticles($itemLister->getArticle($slug));
        $this->assertCount($expectedArticles, $articles);
    }

    public function testDataIsProperlyValidatedAndFiltered(): void
    {
        $this->setupArticleData();

        $blogArticleInputFilterFactory = new BlogArticleInputFilterFactory();
        $itemLister                    = new ItemListerFilesystem(
            vfsStream::url('root/posts'),
            new Parser(),
            $blogArticleInputFilterFactory(),
            null,
        );

        $articles = $itemLister->getArticles();
        $this->assertCount(6, $articles);
    }
}
