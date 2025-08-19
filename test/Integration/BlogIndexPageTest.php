<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Integration;

use Laminas\Diactoros\Response\HtmlResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Settermjd\MarkdownBlog\Handler\BlogIndexHandler;
use Symfony\Component\DomCrawler\Crawler;

final class BlogIndexPageTest extends TestCase
{
    use SetupHelperTrait;

    public function testCanRenderTheBlogIndexRouteWithTheTwigRendererWhenPostsAreAvailable(): void
    {
        $this->setupContainer();

        /** @var BlogIndexHandler $handler */
        $handler = $this->container->get(BlogIndexHandler::class);
        $response = $handler->handle($this->container->get(ServerRequestInterface::class)());

        self::assertInstanceOf(HtmlResponse::class, $response);
        $crawler = new Crawler($response->getBody()->getContents());
        $subCrawler = $crawler->filterXPath('//div[@id="blog-items"]/div');
        self::assertCount(2, $subCrawler);
    }

    public function testCanRenderTheBlogIndexRouteWithThePlatesRendererWhenPostsAreAvailable(): void
    {
        $this->setupContainer(ViewLayer::Plates);

        /** @var BlogIndexHandler $handler */
        $handler = $this->container->get(BlogIndexHandler::class);
        $response = $handler->handle($this->container->get(ServerRequestInterface::class)());

        self::assertInstanceOf(HtmlResponse::class, $response);
        $crawler = new Crawler($response->getBody()->getContents());
        $subCrawler = $crawler->filterXPath('//div[@id="blog-items"]/div');
        self::assertCount(2, $subCrawler);
    }
}
