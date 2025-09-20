<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Integration\Items\Adapter;

use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;
use Settermjd\MarkdownBlogTest\Integration\SetupHelperTrait;

final class ItemListerFilesystemTest extends TestCase
{
    use SetupHelperTrait;

    protected function setUp(): void
    {
        $this->setupContainer();
    }

    public function testCanGetArticles(): void
    {
        /** @var ItemListerInterface $lister */
        $lister = $this->container->get(ItemListerInterface::class);

        self::assertCount(14, $lister->getArticles());
    }
}
