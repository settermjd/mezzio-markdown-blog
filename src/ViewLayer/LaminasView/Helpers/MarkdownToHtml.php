<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\ViewLayer\LaminasView\Helpers;

use League\CommonMark\CommonMarkConverter;

/**
 * MarkdownToHtml is a simplistic extension for laminas-view to convert a Markdown
 * string into the equivalent HTML using CommonMarkConverter from the League of
 * Extraordinary Packages.
 */
final class MarkdownToHtml
{
    public function __invoke(string $markdown): string
    {
        $converter = new CommonMarkConverter();
        return $converter
            ->convert($markdown)
            ->getContent();
    }
}
