<?php

namespace App\Infrastructure\ServiceProviders;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidFactoryInterface;

class UuidServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        UuidFactoryInterface::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(UuidFactoryInterface::class, function () {
            return new UuidFactory();
        });
    }
}
