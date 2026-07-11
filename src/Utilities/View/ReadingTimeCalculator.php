<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Utilities\View;

use TimeConstants;

use function gmdate;
use function round;
use function str_word_count;

use const PHP_ROUND_HALF_UP;

final readonly class ReadingTimeCalculator
{
    /**
     * @return array{'minutes': int, 'seconds': int}
     */
    public function __invoke(
        ReadingType $readingType,
        ReadingProficiency $readingProficiency,
        string $text,
    ): array {
        $readingSpeed = $this->getReadingProficiency($readingType, $readingProficiency);
        $readingTime  = round(
            (TimeConstants\MINUTE_IN_SECONDS * str_word_count($text)) / $readingSpeed,
            mode: PHP_ROUND_HALF_UP
        );

        if ($readingTime < TimeConstants\MINUTE_IN_SECONDS) {
            return [
                'minutes' => 0,
                'seconds' => (int) $readingTime,
            ];
        }

        return [
            'minutes' => (int) gmdate("i", (int) $readingTime),
            'seconds' => (int) gmdate("s", (int) $readingTime),
        ];
    }

    private function getReadingProficiency(
        ReadingType $readingType,
        ReadingProficiency $readingSpeed,
    ): int {
        if ($readingType === ReadingType::Aloud) {
            return match ($readingSpeed) {
                ReadingProficiency::Slow => 100,
                ReadingProficiency::Average => 130,
                ReadingProficiency::Fast => 160,
            };
        }

        return match ($readingSpeed) {
            ReadingProficiency::Slow => 150,
            ReadingProficiency::Average => 250,
            ReadingProficiency::Fast => 400,
        };
    }
}
