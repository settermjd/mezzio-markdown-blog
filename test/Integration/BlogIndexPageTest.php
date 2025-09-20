<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Integration;

use Laminas\Diactoros\Response\HtmlResponse;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Settermjd\MarkdownBlog\Handler\BlogArticleHandler;
use Settermjd\MarkdownBlog\Handler\BlogIndexHandler;
use Settermjd\MarkdownBlog\ViewLayer;
use Symfony\Component\DomCrawler\Crawler;

final class BlogIndexPageTest extends TestCase
{
    use SetupHelperTrait;

    #[TestWith([ViewLayer::LaminasView, 1, 10])]
    #[TestWith([ViewLayer::LaminasView, 2, 3])]
    #[TestWith([ViewLayer::Plates, 1, 10])]
    #[TestWith([ViewLayer::Twig, 1, 10])]
    public function testCanRenderTheBlogIndexRouteWhenPostsAreAvailable(
        ViewLayer $viewLayer,
        int $pageNumber,
        int $blogItemCount
    ): void {
        $_ENV['TEMPLATE_LAYER'] = $viewLayer->value;

        $this->setupContainer($viewLayer);

        /** @var BlogIndexHandler $handler */
        $handler = $this->container->get(BlogIndexHandler::class);
        /** @var ServerRequestInterface $request */
        $request  = $this->container->get(ServerRequestInterface::class)();
        $response = $handler->handle(
            $request->withAttribute(
                'current',
                $pageNumber,
            )
        );

        self::assertInstanceOf(HtmlResponse::class, $response);
        $body    = $response->getBody()->getContents();
        $crawler = new Crawler($body);

        $subCrawler = $crawler->filterXPath('//div[@id="blog-items"]/div');
        self::assertCount($blogItemCount, $subCrawler);
    }

    #[TestWith([ViewLayer::LaminasView], "Test rendering a blog article using the laminas-view template renderer")]
    #[TestWith([ViewLayer::Plates], "Test rendering a blog article using the Plates template renderer")]
    #[TestWith([ViewLayer::Twig], "Test rendering a blog article using the Twig template renderer")]
    public function testCanRenderIndividualBlogItemsWhenMatchingPostsAreAvailable(ViewLayer $viewLayer): void
    {
        $_ENV['TEMPLATE_LAYER'] = $viewLayer->value;

        $this->setupContainer($viewLayer);

        /** @var BlogArticleHandler $handler */
        $handler  = $this->container->get(BlogArticleHandler::class);
        $response = $handler->handle(
            $this->createConfiguredMock(
                ServerRequestInterface::class,
                [
                    'getAttribute' => 'blogArticle-0011',
                ]
            )
        );

        self::assertInstanceOf(HtmlResponse::class, $response);
        $body    = $response->getBody()->getContents();
        $crawler = new Crawler($body);
        self::assertSame(
            'Item 11 - The Life of a Developer Evangelist, with Developer Jack',
            $crawler->filterXPath('//h1')->text()
        );
    }
}
