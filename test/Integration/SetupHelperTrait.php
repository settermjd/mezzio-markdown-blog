<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Integration;

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\ServiceManager;
use Mezzio\ConfigProvider;
use Settermjd\MarkdownBlog\ViewLayer\LaminasView\Helpers\MarkdownToHtml;

trait SetupHelperTrait
{
    private ServiceManager $container;

    public function setupContainer(ViewLayer $viewLayer = ViewLayer::Twig)
    {
        $configuration = [
            ConfigProvider::class,
            \Mezzio\Helper\ConfigProvider::class,
            \Mezzio\Router\ConfigProvider::class,
            \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
            match ($viewLayer) {
                ViewLayer::LaminasView => \Mezzio\LaminasView\ConfigProvider::class,
                ViewLayer::Plates => \Mezzio\Plates\ConfigProvider::class,
                ViewLayer::Twig => \Mezzio\Twig\ConfigProvider::class,
            },
            \Settermjd\MarkdownBlog\ConfigProvider::class,
            new class ($viewLayer) {
                public function __construct(private readonly ViewLayer $viewLayer)
                {
                }

                public function __invoke(): array
                {
                    return [
                        'templates'    => [
                            'paths' => [
                                'app'    => [__DIR__ . '/../_data/templates/app'],
                                'error'  => [__DIR__ . '/../_data/templates/error'],
                                'layout' => [__DIR__ . "/../_data/templates/layout/{$this->viewLayer->value}"],
                            ],
                        ],
                        'view_helpers' => [
                            'aliases'   => [
                                'markdown_to_html' => MarkdownToHtml::class,
                            ],
                            'factories' => [
                                MarkdownToHtml::class => InvokableFactory::class,
                            ],
                        ],
                    ];
                }
            },
        ];
        $configAggregator = new ConfigAggregator($configuration);
        $config           = $configAggregator->getMergedConfig();

        $dependencies                                       = $config['dependencies'];
        $dependencies['services']['config']                 = $config;
        $dependencies['services']['config']['blog']['path'] = __DIR__ . '/../_data/posts';

        $this->container = new ServiceManager($dependencies);
    }
}
