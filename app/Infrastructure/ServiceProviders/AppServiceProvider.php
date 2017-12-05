<?php

namespace App\Infrastructure\ServiceProviders;

use Dotenv\Dotenv;
use Exception;
use League\Container\ReflectionContainer;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Psr\Container\ContainerInterface;

class AppServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * @var array
     */
    protected $provides = [
        ContainerInterface::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(ContainerInterface::class, function () {
            return $this->container;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->container->delegate(new ReflectionContainer());

        try {
            (new Dotenv(__DIR__.'/../../..'))->load();
        } catch (Exception $exception) {
            // Do nothing.
        }
    }
}
