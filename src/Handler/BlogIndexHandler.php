<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Handler;

use ArrayIterator;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;
use Settermjd\MarkdownBlog\Iterator\PublishedItemFilterIterator;
use Settermjd\MarkdownBlog\Sorter\SortByReverseDateOrder;

use function ceil;
use function iterator_count;
use function usort;

final readonly class BlogIndexHandler implements RequestHandlerInterface
{
    /**
     * The default number of records per page, if the amount isn't provided in
     * the constructor
     */
    public const int RECORDS_PER_PAGE = 10;

    /**
     * The default page to render, if the page is not available in the request's
     * query parameters
     */
    public const int DEFAULT_PAGE = 1;

    public function __construct(
        private TemplateRendererInterface $renderer,
        private ItemListerInterface $itemLister,
        private int|null $recordsPerPage = self::RECORDS_PER_PAGE
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $articles = $this->itemLister->getArticles();
        usort($articles, new SortByReverseDateOrder());

        $publishedArticles = new PublishedItemFilterIterator(
            new ArrayIterator($articles)
        );

        $currentPage = $request->getQueryParams()['current'] ?? 1;
        $pageCount   = (int) ceil(iterator_count($publishedArticles) / $this->recordsPerPage);

        $data = [
            'articles'  => $publishedArticles,
            'current'   => $currentPage,
            'pageCount' => $pageCount,
            'previous'  => $currentPage > self::DEFAULT_PAGE,
            'next'      => $currentPage < $pageCount,
        ];

        return new HtmlResponse($this->renderer->render('blog::blog', $data));
    }
}
