<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Integration;

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ServiceManager\ServiceManager;

trait SetupHelperTrait
{
    private ServiceManager $container;

    public function setupContainer(ViewLayer $viewLayer = ViewLayer::Twig)
    {
        $viewRenderer = match ($viewLayer) {
            ViewLayer::LaminasView => ConfigProvider::class,
            ViewLayer::Plates => \Mezzio\Plates\ConfigProvider::class,
            ViewLayer::Twig => \Mezzio\Twig\ConfigProvider::class,
        };

        $configuration = [
            \Mezzio\ConfigProvider::class,
            \Mezzio\Helper\ConfigProvider::class,
            \Mezzio\Router\ConfigProvider::class,
            \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
            $viewRenderer,
            \Settermjd\MarkdownBlog\ConfigProvider::class,
            new class ($viewLayer)
            {
                public function __construct(private readonly ViewLayer $viewLayer)
                {
                }

                public function __invoke(): array
                {
                    return [
                        'templates' => [
                            'paths' => [
                                'app'    => [__DIR__ . '/../_data/templates/app'],
                                'error'  => [__DIR__ . '/../_data/templates/error'],
                                'layout' => [__DIR__ . "/../_data/templates/layout/{$this->viewLayer->value}"],
                            ],
                        ],
                    ];
                }
            },
        ];
        $configAggregator = new ConfigAggregator($configuration);
        $config = $configAggregator->getMergedConfig();

        $dependencies                                 = $config['dependencies'];
        $dependencies['services']['config']           = $config;
        $dependencies['services']['config']['blog']['path']  = __DIR__ . '/../_data/posts';

        $this->container = new ServiceManager($dependencies);
    }
}
