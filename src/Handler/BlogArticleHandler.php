<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;

final readonly class BlogArticleHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private ItemListerInterface $itemLister
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $article = $this->itemLister->getArticle($request->getAttribute('slug'));
        $data    = [
            'article'         => $article,
            'relatedArticles' => $this->itemLister->getRelatedArticles($article),
        ];

        return new HtmlResponse($this->renderer->render('blog::blog-article', $data));
    }
}
