<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\Utilities\View;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\Utilities\View\ReadingProficiency;
use Settermjd\MarkdownBlog\Utilities\View\ReadingTimeCalculator;
use Settermjd\MarkdownBlog\Utilities\View\ReadingType;

use function file_get_contents;
use function sprintf;

class ReadingTimeCalculatorTest extends TestCase
{
    #[TestWith([ReadingType::Aloud, ReadingProficiency::Slow, 50, ['minutes' => 0, 'seconds' => 30]])]
    #[TestWith([ReadingType::Aloud, ReadingProficiency::Average, 50, ['minutes' => 0, 'seconds' => 23]])]
    #[TestWith([ReadingType::Aloud, ReadingProficiency::Fast, 50, ['minutes' => 0, 'seconds' => 19]])]
    #[TestWith([ReadingType::Silent, ReadingProficiency::Slow, 50, ['minutes' => 0, 'seconds' => 20]])]
    #[TestWith([ReadingType::Silent, ReadingProficiency::Average, 50, ['minutes' => 0, 'seconds' => 12]])]
    #[TestWith([ReadingType::Silent, ReadingProficiency::Fast, 50, ['minutes' => 0, 'seconds' => 8]])]
    #[TestWith([ReadingType::Aloud, ReadingProficiency::Slow, 3750, ['minutes' => 37, 'seconds' => 30]])]
    #[TestWith([ReadingType::Aloud, ReadingProficiency::Average, 3750, ['minutes' => 28, 'seconds' => 51]])]
    #[TestWith([ReadingType::Aloud, ReadingProficiency::Fast, 3750, ['minutes' => 23, 'seconds' => 26]])]
    #[TestWith([ReadingType::Silent, ReadingProficiency::Slow, 3750, ['minutes' => 25, 'seconds' => 0]])]
    #[TestWith([ReadingType::Silent, ReadingProficiency::Average, 3750, ['minutes' => 15, 'seconds' => 0]])]
    #[TestWith([ReadingType::Silent, ReadingProficiency::Fast, 3750, ['minutes' => 9, 'seconds' => 23]])]
    /**
     * @param array{'minutes': int, 'seconds': int} $expectedReadingTime
     */
    public function testCanCalculateReadingTime(
        ReadingType $readingType,
        ReadingProficiency $readingSpeed,
        int $wordCount,
        array $expectedReadingTime,
    ): void {
        $text = file_get_contents(__DIR__ . sprintf("/../../../_data/sample-text/%d-words.txt", $wordCount));

        $readingTime = new ReadingTimeCalculator()
            ->__invoke($readingType, $readingSpeed, $text);
        self::assertSame($expectedReadingTime, $readingTime);
    }
}
