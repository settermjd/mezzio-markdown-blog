<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Unit;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Mezzio\Application;
use Mni\FrontYAML\Parser;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\ConfigProvider;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;

use function array_keys;
use function str_ends_with;

class ConfigProviderTest extends TestCase
{
    public function testHasTheRequiredDependencies(): void
    {
        $configProvider = new ConfigProvider();

        $dependencies = $configProvider->getDependencies();
        self::assertSame(
            [
                'abstract_factories',
                'delegators',
                'factories',
            ],
            array_keys($dependencies),
        );
        self::assertContains(
            ReflectionBasedAbstractFactory::class,
            $dependencies['abstract_factories']
        );
        self::assertArrayHasKey(
            ItemListerInterface::class,
            $dependencies['factories'],
        );
        self::assertArrayHasKey(
            InputFilterInterface::class,
            $dependencies['factories'],
        );
        self::assertArrayHasKey(
            Application::class,
            $dependencies['delegators'],
        );

        $routes = $configProvider->getRoutes();
        self::assertCount(2, $routes);

        $config = $configProvider();
        self::assertArrayHasKey('blog', $config);
        self::assertSame('filesystem', $config['blog']['type']);
        self::assertTrue(str_ends_with($config['blog']['path'], '/../../data/posts'));
        self::assertSame(Parser::class, $config['blog']['parser']);
    }
}
