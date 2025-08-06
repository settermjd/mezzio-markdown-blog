<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Sorter;

use Exception;
use Settermjd\MarkdownBlog\Entity\BlogArticle;

/**
 * A simple invokable class to help sort a list of episodes.
 */
final class SortByReverseDateOrder
{
    /**
     * Sort the entries in reverse date order.
     *
     * @throws Exception
     */
    public function __invoke(BlogArticle $a, BlogArticle $b): int
    {
        $firstDate  = $a->getPublishDate();
        $secondDate = $b->getPublishDate();

        if ($firstDate === $secondDate) {
            return 0;
        }

        return $firstDate > $secondDate ? -1 : 1;
    }
}
