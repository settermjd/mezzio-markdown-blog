<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Settermjd\MarkdownBlog\Handler\BlogArticleHandler;
use Settermjd\MarkdownBlog\Handler\BlogIndexHandler;
use Settermjd\MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use Settermjd\MarkdownBlog\Items\ItemListerFactory;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;

/**
 * The configuration provider for the module
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
            'routes'       => $this->getRoutes(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array<string,array<int,class-string|class-string,array<int,class-string>|class-string,class-string>>
     */
    public function getDependencies(): array
    {
        return [
            'abstract_factories' => [
                ReflectionBasedAbstractFactory::class,
            ],
            'delegators'         => [
                Application::class => [
                    ApplicationConfigInjectionDelegator::class,
                ],
            ],
            'factories'          => [
                ItemListerInterface::class  => ItemListerFactory::class,
                InputFilterInterface::class => BlogArticleInputFilterFactory::class,
            ],
        ];
    }

    public function getRoutes(): array
    {
        return [
            // Add the route for the blog index page with support for pagination
            [
                'path'            => '/blog[/{current:\d+}]',
                'name'            => 'blog.index',
                'middleware'      => [
                    BlogIndexHandler::class,
                ],
                'allowed_methods' => ['GET'],
            ],

            // Add the route for viewing a blog item
            [
                'path'            => '/blog/item/{slug}',
                'name'            => 'blog.item.view',
                'middleware'      => [
                    BlogArticleHandler::class,
                ],
                'allowed_methods' => ['GET'],
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'blog' => [
                    __DIR__ . '/../templates/blog'
                ],
            ],
        ];
    }
}
