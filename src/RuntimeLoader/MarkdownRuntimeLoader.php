<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\RuntimeLoader;

use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

final class MarkdownRuntimeLoader implements RuntimeLoaderInterface
{
    public function load(string $class): MarkdownRuntime|null
    {
        if (MarkdownRuntime::class === $class) {
            return new MarkdownRuntime(new DefaultMarkdown());
        }

        return null;
    }
}
