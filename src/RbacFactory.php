<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Wuxian\Rbac;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class RbacFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ConfigInterface
     */
    private $config;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $container->get(ConfigInterface::class);
    }

    public function get($adapterName): Filesystem
    {
        $options = $this->config->get('rbac', [
            'default' => 'local',
            'storage' => [
                'local' => [
                    'driver' => LocalAdapterFactory::class,
                    'root' => BASE_PATH . '/runtime',
                ],
            ],
        ]);
        $adapter = $this->getAdapter($options, $adapterName);
        return new Filesystem($adapter, new Config($options['storage'][$adapterName]));
    }

    public function getAdapter($options, $adapterName)
    {
        if (! $options['storage'] || ! $options['storage'][$adapterName]) {
            throw new InvalidArgumentException("file configurations are missing {$adapterName} options");
        }
        /** @var AdapterFactoryInterface $driver */
        $driver = $this->container->get($options['storage'][$adapterName]['driver']);
        return $driver->make($options['storage'][$adapterName]);
    }
}
