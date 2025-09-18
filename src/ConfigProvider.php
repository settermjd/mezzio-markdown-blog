<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Mni\FrontYAML\Parser;
use Settermjd\MarkdownBlog\Handler\BlogArticleHandler;
use Settermjd\MarkdownBlog\Handler\BlogIndexHandler;
use Settermjd\MarkdownBlog\InputFilter\BlogArticleInputFilterFactory;
use Settermjd\MarkdownBlog\Items\ItemListerFactory;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;
use Settermjd\MarkdownBlog\ViewLayer\Plates\Extensions\MarkdownToHtml;
use Settermjd\MarkdownBlog\ViewLayer;
use Twig\Extra\Intl\IntlExtension;
use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

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
     * @return array{"blog": array, "dependencies"?: array, "routes"?: array, "templates"?: array}
     */
    public function __invoke(): array
    {
        return [
            'blog'         => $this->getBlogConfig(),
            'dependencies' => $this->getDependencies(),
            'routes'       => $this->getRoutes(),
            'templates'    => $this->getTemplates(),
            'twig'         => $this->getTwigConfig(),
            'plates'       => $this->getPlatesConfig(),
            //$this->getLaminasViewConfig(),
            'view_helpers' => [
                "markdown_to_html" => new MarkdownToHtml(),
            ],
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array{"abstract_factories"?: array, "delegators"?: array, "factories"?: array}
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

    /**
     * @return array<int,array<string,string|array<int,class-string|string>>>
     */
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
        $templateLayer = $_ENV['TEMPLATE_LAYER'] ?? ViewLayer::Twig->value;

        return [
            'paths' => [
                'blog' => [
                    __DIR__ . "/../templates/blog/$templateLayer",
                ],
            ],
        ];
    }

    /**
     * getBlogConfig returns a default configuration for the blog.
     *
     * This avoids users having to copy a config file to their local config/autoload directory.
     * However, a default file is provided in the package's config/autoload directory.
     *
     * @return array{"type": string, "path": string, "parser": class-string}
     */
    public function getBlogConfig(): array
    {
        $path = $_ENV['BLOG_POSTS_DIR'] ?? 'data/posts';

        return [
            /**
             * Setting type to 'filesystem', which currently is the only choice,
             * will invoke the ItemListerFilesystem adapter to retrieve blog files
             * from the local filesystem.
             */
            'type' => $_ENV['BLOG_ADAPTER_TYPE'] ?? 'filesystem',

            /**
             * 'path' sets the path on the local filesystem to retrieve the Markdown
             * files from. This directory needs to be manually initialised before it
             * can be used.
             */
            'path' => __DIR__ . '/../../../' . $path,

            /**
             * 'parser' is the class to use to parse the Markdown file's YAML front-matter.
             * In future releases, other front-matter formats may be supported. However,
             * for the time being, only YAML is supported.
             */
            'parser' => Parser::class,
        ];
    }

    /**
     * getTwigConfig returns a default configuration for Twig
     *
     * This avoids users having to copy a config file to their local config/autoload directory.
     * However, a default file is provided in the package's config/autoload directory.
     *
     * @return array{"extensions": array, "runtime_loaders": array}
     */
    public function getTwigConfig(): array
    {
        return [
            'extensions'      => [
                new IntlExtension(),
                new MarkdownExtension(),
            ],
            'runtime_loaders' => [
                new class implements RuntimeLoaderInterface {
                    public function load(string $class): MarkdownRuntime|null
                    {
                        return MarkdownRuntime::class === $class
                            ? new MarkdownRuntime(new DefaultMarkdown())
                            : null;
                    }
                },
            ],
        ];
    }

    /**
     * getPlatesConfig returns a default configuration for Plates
     *
     * This avoids users having to copy a config file to their local config/autoload directory.
     * However, a default file is provided in the package's config/autoload directory.
     *
     * @return array{"extensions": array}
     */
    public function getPlatesConfig(): array
    {
        return [
            'extensions' => [
                new MarkdownToHtml(),
            ],
        ];
    }

    public function getLaminasViewConfig(): array
    {
        return [
            'view_helpers' => [
                new MarkdownToHtml(),
            ],
        ];
    }
}
