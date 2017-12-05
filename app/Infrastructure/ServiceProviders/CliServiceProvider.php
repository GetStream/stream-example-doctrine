<?php

namespace App\Infrastructure\ServiceProviders;

use App\Commands\SeedCommand;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Ramsey\Uuid\UuidFactoryInterface;

class CliServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        SeedCommand::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->add(SeedCommand::class, function () {
            return new SeedCommand(
                $this->container->get(EntityManagerInterface::class),
                $this->container->get(Generator::class),
                $this->container->get(UuidFactoryInterface::class),
                $this->container->get('base_path').'/resources/fixtures'
            );
        });
    }
}
