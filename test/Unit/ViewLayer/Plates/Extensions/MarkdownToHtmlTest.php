<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\ViewLayer\Plates\Extensions;

use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\ViewLayer\Plates\Extensions\MarkdownToHtml;

class MarkdownToHtmlTest extends TestCase
{
    /**
     * This is a simple test to check that the extension converts Markdown to
     * HTML properly. It's not an extensive one, as that's provided by the
     * underlying package.
     */
    public function testCanConvertMarkdownToHtml(): void
    {
        $extension = new MarkdownToHtml();
        self::assertSame(
            '<h1>Hello, World!</h1>',
            trim($extension->markdownToHtml('# Hello, World!')),
        );
    }
}