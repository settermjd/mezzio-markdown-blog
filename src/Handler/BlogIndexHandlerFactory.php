<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Settermjd\MarkdownBlog\Handler\BlogIndexHandler;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;

class BlogIndexHandlerFactory
{
    public function __invoke(ContainerInterface $container): BlogIndexHandler
    {
        return new BlogIndexHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(ItemListerInterface::class),
            $container->get("config")['blog']['items_per_page']
        );
    }
}
