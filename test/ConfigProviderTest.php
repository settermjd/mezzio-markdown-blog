<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Mezzio\Application;
use Mni\FrontYAML\Parser;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\ConfigProvider;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;

use function array_keys;

class ConfigProviderTest extends TestCase
{
    public function testHasTheRequiredDependencies(): void
    {
        $configProvider = new ConfigProvider();

        $dependencies = $configProvider->getDependencies();
        $this->assertSame(
            [
                'abstract_factories',
                'delegators',
                'factories',
            ],
            array_keys($dependencies),
        );
        $this->assertContains(
            ReflectionBasedAbstractFactory::class,
            $dependencies['abstract_factories']
        );
        $this->assertArrayHasKey(
            ItemListerInterface::class,
            $dependencies['factories'],
        );
        $this->assertArrayHasKey(
            InputFilterInterface::class,
            $dependencies['factories'],
        );
        $this->assertArrayHasKey(
            Application::class,
            $dependencies['delegators'],
        );

        $routes = $configProvider->getRoutes();
        $this->assertCount(2, $routes);

        $config = $configProvider();
        $this->assertArrayHasKey('blog', $config);
        $this->assertSame('filesystem', $config['blog']['type']);
        $this->assertTrue(str_ends_with($config['blog']['path'], '/../../../data/posts'));
        $this->assertSame(Parser::class, $config['blog']['parser']);
    }
}
