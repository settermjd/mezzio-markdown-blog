<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\ViewLayer\Plates\Extensions;

use League\CommonMark\CommonMarkConverter;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

/**
 * MarkdownToHtml is a simplistic extension for Plates to convert a Markdown
 * string into the equivalent HTML using CommonMarkConverter from the League of
 * Extraordinary Packages.
 */
final class MarkdownToHtml implements ExtensionInterface
{
    public function register(Engine $engine)
    {
        $engine->registerFunction('markdown_to_html', [$this, 'markdownToHtml']);
    }

    public function markdownToHtml(string $markdown): string
    {
        $converter = new CommonMarkConverter();
        return $converter
            ->convert($markdown)
            ->getContent();
    }
}
