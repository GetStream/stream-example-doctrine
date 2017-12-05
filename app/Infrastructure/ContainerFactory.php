<?php

namespace App\Infrastructure;

use League\Container\Container;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    /**
     * @param string $basePath
     *
     * @return ContainerInterface
     */
    public static function create($basePath)
    {
        $container = new Container();

        $container->share('base_path', $basePath);

        $container->addServiceProvider(ServiceProviders\AppServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\DBServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\StreamServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\RouteServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\SessionServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\ViewServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\ViewServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\CliServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\MigrationServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\RepositoryServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\CommandBusServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\UuidServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\LogServiceProvider::class);
        $container->addServiceProvider(ServiceProviders\MiddlewareServiceProvider::class);

        return $container;
    }
}
