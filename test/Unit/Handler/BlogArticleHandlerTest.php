<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Settermjd\MarkdownBlog\Entity\BlogArticle;
use Settermjd\MarkdownBlog\Handler\BlogArticleHandler;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;

class BlogArticleHandlerTest extends TestCase
{
    public function testCanHandleGetRequestWhenAnArticleMatchesTheSuppliedSlug(): void
    {
        $slug     = 'dockerfile-buildargs-go-out-of-scope';
        $template = $this->createMock(TemplateRendererInterface::class);
        $template
            ->expects($this->once())
            ->method('render')
            ->with('blog::blog-article', [
                'article' => new BlogArticle(),
            ]);

        $itemLister = $this->createMock(ItemListerInterface::class);
        $itemLister
            ->expects($this->once())
            ->method('getArticle')
            ->with($slug)
            ->willReturn(new BlogArticle());

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->willReturn($slug);

        $handler = new BlogArticleHandler($template, $itemLister);
        $this->assertInstanceOf(HtmlResponse::class, $handler->handle($request));
    }
}
