<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit\Iterator;

use DateInterval;
use DateTime;
use org\bovigo\vfs\vfsStream;

use function file_get_contents;
use function sprintf;

trait DataTrait
{
    /** @var array<string,array<string,string>> */
    private array $structure;

    public function setupArticleData(): void
    {
        $item001Content = file_get_contents(__DIR__ . '/../_data/posts/item-0001.md');
        $item002Content = file_get_contents(__DIR__ . '/../_data/posts/item-0002.md');
        $item003Content = sprintf(
            file_get_contents(__DIR__ . '/../_data/posts/item-0003.md'),
            (new DateTime())->add(new DateInterval('P3D'))->format('d.m.Y')
        );
        $item004Content = sprintf(
            file_get_contents(__DIR__ . '/../_data/posts/item-0004.md'),
            (new DateTime())->add(new DateInterval('P5D'))->format('d.m.Y')
        );
        $item005Content = sprintf(
            file_get_contents(__DIR__ . '/../_data/posts/item-0005.md'),
            (new DateTime())->sub(new DateInterval('P1D'))->format('d.m.Y')
        );
        $item006Content = file_get_contents(__DIR__ . '/../_data/posts/item-0001.md');

        $this->structure = [
            'posts' => [
                'item-0001.md' => $item001Content,
                'item-0002.md' => $item002Content,
                'item-0003.md' => $item003Content,
                'item-0004.md' => $item004Content,
                'item-0005.md' => $item005Content,
                'item-0006.md' => $item006Content,
            ],
        ];
        vfsStream::setup('root', null, $this->structure);
    }
}
