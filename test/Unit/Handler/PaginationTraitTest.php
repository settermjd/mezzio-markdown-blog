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
}
