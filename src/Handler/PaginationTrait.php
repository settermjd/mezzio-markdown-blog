<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Handler;

/**
 * This trait contains utility functions for handling and simplifying pagination
 *
 * They're stored here, instead of directly in a handler class, as there may be
 * many handlers that need them, but not all of them may. So they can be used as
 * and when necessary.
 */
trait PaginationTrait
{
    public function getItemLimitOffset(
        int $currentPage = 1,
        int $itemsPerPage = BlogIndexHandler::ITEMS_PER_PAGE
    ): int {
        return $currentPage === 1 ? 0 : ($currentPage - 1) * $itemsPerPage;
    }
}
