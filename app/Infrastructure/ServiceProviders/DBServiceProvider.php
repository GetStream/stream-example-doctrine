<?php

namespace App\Infrastructure\ServiceProviders;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Ramsey\Uuid\Doctrine\UuidType;

class DBServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * @var array
     */
    protected $provides = [
        EntityManagerInterface::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(EntityManagerInterface::class, function () {
            $basePath = $this->container->get('base_path');

            // the connection configuration
            if (getenv('DATABASE_URL')) {
                $dbParams = ['url' => getenv('DATABASE_URL')];
            } else {
                $dbParams = [
                    'driver' => 'pdo_sqlite',
                    'path' => $basePath . '/storage/db.sqlite',
                ];
            }

            $config = Setup::createAnnotationMetadataConfiguration(
                [$basePath . '/app/Models/'],
                true,
                null,
                null,
                false
            );

            return EntityManager::create($dbParams, $config);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        Type::addType('uuid', UuidType::class);
    }
}
