<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Items;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Settermjd\MarkdownBlog\Items\Adapter\ItemListerFilesystem;

use function array_key_exists;
use function sprintf;

final class ItemListerFactory
{
    /**
     * Build an ItemListerInterface object based on a configuration array.
     *
     * The array has to have the following structure:
     *
     * 'blog' => [
     *     'type' => 'filesystem',
     *     'path' => __DIR__ . '/../../data/posts',
     *     'parser' => Parser::class,
     * ]
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ItemListerFilesystem
    {
        $config = (array) $container->get('config');
        if ($config === [] || ! array_key_exists('blog', $config) || $config['blog'] === []) {
            throw new InvalidServiceException('Blog configuration was invalid.');
        }
        $blogConfig = $config['blog'];

        $inputFilter = $container->get(InputFilterInterface::class);
        if (! $inputFilter instanceof InputFilterInterface) {
            throw new InvalidServiceException('Input filter is invalid.');
        }

        $parser = $container->get($blogConfig['parser']);
        if (! $parser instanceof $blogConfig['parser']) {
            throw new InvalidServiceException(sprintf(
                'Parse is not of the correct type. Received %s, but was expecting %s.',
                $parser,
                $blogConfig['parser']
            ));
        }

        switch ($blogConfig['type']) {
            case 'filesystem':
            default:
                return new ItemListerFilesystem(
                    $blogConfig['path'],
                    $parser,
                    $inputFilter,
                    $container->has(CacheItemPoolInterface::class)
                        ? $container->get(CacheItemPoolInterface::class)
                        : null,
                    $container->has(LoggerInterface::class)
                        ? $container->get(LoggerInterface::class)
                        : null
                );
        }
    }
}
