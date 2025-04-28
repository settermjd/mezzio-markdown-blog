<?php

declare(strict_types=1);

namespace MarkdownBlog;

use Laminas\InputFilter\InputFilterInterface;
use MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use MarkdownBlog\Items\ItemListerFactory;
use MarkdownBlog\Items\ItemListerInterface;

/**
 * The configuration provider for the MarkdownBlog module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
final class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return string[][][]
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return string[][]
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                ItemListerInterface::class  => ItemListerFactory::class,
                InputFilterInterface::class => BlogArticleInputFilterFactory::class,
            ],
        ];
    }
}
