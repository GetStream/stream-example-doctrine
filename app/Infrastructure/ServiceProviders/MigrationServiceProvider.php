<?php

namespace App\Infrastructure\ServiceProviders;

use Faker\Factory;
use Faker\Generator;
use League\Container\ServiceProvider\AbstractServiceProvider;

class MigrationServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        Generator::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(Generator::class, function () {
            return Factory::create();
        });
    }
}
