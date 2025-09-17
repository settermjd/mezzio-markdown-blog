<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Items;

use Iterator;
use Settermjd\MarkdownBlog\Entity\BlogArticle;

/**
 * Interface ItemListerInterface.
 */
interface ItemListerInterface
{
    /**
     * Return the available articles.
     *
     * @return array<int,BlogArticle>
     */
    public function getArticles(): array;

    /**
     * Returns an array of BlogArticles that are related to the supplied one
     */
    public function getRelatedArticles(BlogArticle $blogArticle): Iterator;

    /**
     * Returns a single article matching the slug provided
     */
    public function getArticle(string $slug): BlogArticle|string;

    /**
     * This function returns a unique and sorted scalar array of all categories
     * referenced in the current items list.
     *
     * @return array<int,string>
     */
    public function getCategories(): array;

    /**
     * This function returns a unique and sorted scalar array of tags
     * referenced in the current items list.
     *
     * @return array<int,string>
     */
    public function getTags(): array;
}
