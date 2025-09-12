<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Integration;

enum ViewLayer: string
{
    case LaminasView = 'laminas';
    case Plates      = 'plates';
    case Twig        = 'twig';
}
