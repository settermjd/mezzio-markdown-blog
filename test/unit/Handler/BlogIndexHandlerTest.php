<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Handler;

use ArrayIterator;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Settermjd\MarkdownBlog\Entity\BlogArticle;
use Settermjd\MarkdownBlog\Handler\BlogIndexHandler;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;
use Settermjd\MarkdownBlog\Iterator\PublishedItemFilterIterator;

class BlogIndexHandlerTest extends TestCase
{
    #[DataProvider('blogIndexDataProvider')]
    public function testCanRenderBlogItemsWhenTheyAreAvailable(
        array $articles,
        int $currentPage,
        int $pageCount,
        bool $previous,
        bool $next
    ) {
        $publishedArticles = new PublishedItemFilterIterator(new ArrayIterator($articles));
        $data              = [
            'articles'  => $publishedArticles,
            'current'   => $currentPage,
            'pageCount' => $pageCount,
            'previous'  => $previous,
            'next'      => $next,
        ];

        /** @var TemplateRendererInterface&MockObject $template */
        $template = $this->createMock(TemplateRendererInterface::class);
        $template
            ->expects($this->once())
            ->method("render")
            ->with("blog::blog", $data);

        /** @var ItemListerInterface&MockObject $itemLister */
        $itemLister = $this->createMock(ItemListerInterface::class);
        $itemLister
            ->expects($this->once())
            ->method("getArticles")
            ->willReturn($articles);

        /** @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getQueryParams")
            ->willReturn([
                "current" => $currentPage,
            ]);

        $handler = new BlogIndexHandler($template, $itemLister);

        $response = $handler->handle($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @return array<string, list<bool|int|list<BlogArticle>>>
     */
    public static function blogIndexDataProvider(): array
    {
        return [
            // phpcs:disable Generic.Files.LineLength
            "One blog item is available, and we're on the first page of records without the ability to move forward or back"        => [
                [
                    new BlogArticle([
                        'slug'        => "/article-one",
                        'title'       => "Article One",
                        "publishDate" => "10.03.2021",
                    ]),
                ],
                1,
                1,
                false,
                false,
            ],
            "On the second of two pages of blog items, showing one item per page, with the ability to go back to the previous page" => [
                [
                    new BlogArticle([
                        'slug'        => "/article-one",
                        'title'       => "Article One",
                        "publishDate" => "10.03.2021",
                    ]),
                    new BlogArticle([
                        'slug'        => "/article-two",
                        'title'       => "Article Two",
                        "publishDate" => "11.03.2021",
                    ]),
                    new BlogArticle([
                        'slug'        => "/article-three",
                        'title'       => "Article Three",
                        "publishDate" => "12.03.2021",
                    ]),
                    new BlogArticle([
                        'slug'        => "/article-four",
                        'title'       => "Article Four",
                        "publishDate" => "13.03.2021",
                    ]),
                    new BlogArticle([
                        'slug'        => "/article-five",
                        'title'       => "Article Five",
                        "publishDate" => "14.03.2021",
                    ]),
                    new BlogArticle([
                        'slug'        => "/article-six",
                        'title'       => "Article Six",
                        "publishDate" => "15.03.2021",
                    ]),
                ],
                2,
                1,
                true,
                false,
            ],
            // phpcs:enable Generic.Files.LineLength
            "On the first page of one page of blog items with a total of one blog items" => [
                [
                    new BlogArticle([
                        'slug'        => "/article-one",
                        'title'       => "Article One",
                        "publishDate" => "10.03.2021",
                    ]),
                    new BlogArticle([
                        'slug'        => "/article-two",
                        'title'       => "Article Two",
                        "publishDate" => "11.03.2021",
                    ]),
                    new BlogArticle([
                        'slug'        => "/article-three",
                        'title'       => "Article Three",
                        "publishDate" => "12.03.2021",
                    ]),
                    new BlogArticle([
                        'slug'        => "/article-four",
                        'title'       => "Article Four",
                        "publishDate" => "13.03.2021",
                    ]),
                    new BlogArticle([
                        'slug'        => "/article-five",
                        'title'       => "Article Five",
                        "publishDate" => "14.03.2021",
                    ]),
                    new BlogArticle([
                        'slug'        => "/article-six",
                        'title'       => "Article Six",
                        "publishDate" => "15.03.2021",
                    ]),
                ],
                1,
                1,
                false,
                false,
            ],
        ];
    }

    public function testDoesNotRendertutorialsWhenNoneAreAvailable()
    {
        $currentPage = 1;

        $data = [
            'articles'  => new PublishedItemFilterIterator(new ArrayIterator([])),
            'current'   => $currentPage,
            'pageCount' => 0,
            'previous'  => false,
            'next'      => false,
        ];

        /** @var ItemListerInterface&MockObject $itemLister */
        $itemLister = $this->createMock(ItemListerInterface::class);
        $itemLister
            ->expects($this->once())
            ->method("getArticles")
            ->willReturn([]);

        /** @var TemplateRendererInterface&MockObject $template */
        $template = $this->createMock(TemplateRendererInterface::class);
        $template
            ->expects($this->once())
            ->method("render")
            ->with("blog::blog", $data);

        /** @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getQueryParams")
            ->willReturn([
                "current" => $currentPage,
            ]);

        $handler = new BlogIndexHandler($template, $itemLister);

        $response = $handler->handle($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }
}
