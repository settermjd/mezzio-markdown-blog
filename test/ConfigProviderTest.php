<?php
declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Mezzio\Application;
use Settermjd\MarkdownBlog\ConfigProvider;
use PHPUnit\Framework\TestCase;
use Settermjd\MarkdownBlog\Items\ItemListerInterface;

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
            Application::class, $dependencies['delegators'],
        );

        $routes = $configProvider->getRoutes();
        $this->assertCount(2, $routes);
    }
}
