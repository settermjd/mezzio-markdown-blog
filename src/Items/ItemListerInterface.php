<?php

declare(strict_types=1);

namespace MarkdownBlog\Items;

/**
 * Interface ItemListerInterface.
 *
 * @author Matthew Setter <matthew@matthewsetter.com>
 * @copyright 2021 Matthew Setter
 */
interface ItemListerInterface
{
    public function getArticle(string $episodeSlug);

    public function getArticles();

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
