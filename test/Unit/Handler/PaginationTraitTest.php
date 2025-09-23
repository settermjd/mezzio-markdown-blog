<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\Handler;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\Handler\PaginationTrait;

class PaginationTraitTest extends TestCase
{
    use PaginationTrait;

    #[TestWith(
        [1, 10, 0],
        "Offset should be 0 when we're rendering 10 items per page and we're on page 1"
    )]
    #[TestWith(
        [2, 10, 10],
        "Offset should be 10 when we're rendering 10 items per page and we're on page 2"
    )]
    #[TestWith(
        [2, 5, 5],
        "Offset should be 5 when we're rendering 5 items per page and we're on page 2"
    )]
    public function testCanDetermineLimitOffset(
        int $currentPage,
        int $itemsPerPage,
        int $expectedOffset
    ): void {
        $this->assertSame($expectedOffset, $this->getItemLimitOffset($currentPage, $itemsPerPage));
    }

    #[TestWith([1, 10, 10, false])]
    #[TestWith([1, 28, 10, true])]
    #[TestWith([3, 28, 10, false])]
    public function testCanDetermineIfNextPageExistsCorrectly(
        int $currentPage,
        int $totalItems,
        int $itemsPerPage,
        bool $hasNextPage
    ): void {
        $this->assertSame($hasNextPage, $this->hasNextPage($currentPage, $totalItems, $itemsPerPage));
    }

    #[TestWith([1, false])]
    #[TestWith([1, false])]
    #[TestWith([2, true])]
    #[TestWith([3, true])]
    #[TestWith([1, false])]
    public function testCanDetermineIfPreviousPageExistsCorrectly(
        int $currentPage,
        bool $hasPreviousPage,
    ): void {
        $this->assertSame($hasPreviousPage, $this->hasPreviousPage($currentPage));
    }
}
