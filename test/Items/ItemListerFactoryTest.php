<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlogTest\Items;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Mni\FrontYAML\Parser;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Settermjd\MarkdownBlog\Items\Adapter\ItemListerFilesystem;
use Settermjd\MarkdownBlog\Items\ItemListerFactory;
use UnexpectedValueException;

use function sprintf;

final class ItemListerFactoryTest extends TestCase
{
    /** @var array<string,array<string,string|class-string>> */
    private array $config;

    #[Override]
    public function setUp(): void
    {
        $this->config = [
            'blog' => [
                'type'   => 'filesystem',
                'path'   => __DIR__ . '/../_data/posts',
                'parser' => Parser::class,
            ],
        ];
    }

    public function testCanInstantiateItemListerInterfaceObject(): void
    {
        $this->setUp();

        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->atMost(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $this->config,
                $this->createMock(InputFilterInterface::class),
                new Parser(),
                $this->createMock(LoggerInterface::class)
            );
        $container
            ->expects($this->atMost(2))
            ->method('has')
            ->willReturn(false, true);

        $factory    = new ItemListerFactory();
        $itemLister = $factory($container);
        $this->assertInstanceOf(ItemListerFilesystem::class, $itemLister);
    }

    public function testThrowsExceptionIfTestDirectoryIsNotAvailableOrUsable(): void
    {
        $this->setUp();

        $this->expectException(UnexpectedValueException::class);

        $config = [
            'blog' => [
                'type'   => 'filesystem',
                'path'   => __DIR__ . '/../data/posts',
                'parser' => Parser::class,
            ],
        ];

        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->atMost(3))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $config,
                $this->createMock(InputFilterInterface::class),
                new Parser(),
            );

        $container
            ->expects($this->atMost(2))
            ->method('has')
            ->willReturnOnConsecutiveCalls(false, false);

        $factory = new ItemListerFactory();
        $factory($container);
    }

    public function testThrowsExceptionIfParserServiceRetrievedFromTheContainerIsNotOfTheCorrectType(): void
    {
        $this->expectException(InvalidServiceException::class);
        $this->expectExceptionMessage(sprintf(
            'Parse is not of the correct type. Received %s, but was expecting %s.',
            $this->config['blog']['parser'],
            Parser::class,
        ));

        $inputFilter = $this->createMock(InputFilterInterface::class);

        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->atMost(3))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $this->config,
                $inputFilter,
                Parser::class,
            );

        $factory = new ItemListerFactory();
        $factory($container);
    }

    #[DataProvider('invalidBlogConfigurationProvider')]
    public function testThrowsExceptionIfBlogConfigurationIsInvalidOrMissing(array|null $config = null): void
    {
        $this->expectException(InvalidServiceException::class);
        $this->expectExceptionMessage('Blog configuration was invalid.');

        $inputFilter = $this->createMock(InputFilterInterface::class);

        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->atMost(3))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $config,
                $inputFilter,
                Parser::class,
            );
        $container
            ->expects($this->never())
            ->method('has');

        $factory = new ItemListerFactory();
        $factory($container);
    }

    /**
     * @return (null|string[][])[][]
     * @psalm-return list{list{array{blag: array{type: 'filesystem', path: '/home/settermjd/Workspace/PHP/markdown-blog/test/Items/../_data/posts', parser: Parser::class}}}, list{null}}
     */
    public static function invalidBlogConfigurationProvider(): array
    {
        return [
            [
                [
                    'blag' => [
                        'type'   => 'filesystem',
                        'path'   => __DIR__ . '/../_data/posts',
                        'parser' => Parser::class,
                    ],
                ],
            ],
            [
                null,
            ],
        ];
    }

    #[DataProvider('invalidInputFilterProvider')]
    public function testThrowsExceptionIfInputFilterIsInvalidOrMissing(
        InputFilterInterface|null $inputFilter = null
    ): void {
        if (! $inputFilter instanceof InputFilterInterface) {
            $this->expectException(InvalidServiceException::class);
            $this->expectExceptionMessage('Input filter is invalid.');
        }

        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->atMost(3))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $this->config,
                $inputFilter,
                Parser::class,
            );
        $container
            ->expects($this->atMost(2))
            ->method('has')
            ->willReturnOnConsecutiveCalls(true, false);

        $factory = new ItemListerFactory();
        $factory($container);
    }

    /**
     * @return null[][]
     * @psalm-return list{list{null}}
     */
    public static function invalidInputFilterProvider(): array
    {
        return [
            [
                null,
            ],
        ];
    }
}
