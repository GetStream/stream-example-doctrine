<?php

namespace App\Infrastructure\ServiceProviders;

use Doctrine\ORM\EntityManagerInterface;
use GetStream\Doctrine\Enrich;
use GetStream\Doctrine\EnrichInterface;
use GetStream\Doctrine\FeedManager;
use GetStream\Doctrine\FeedManagerInterface;
use GetStream\Doctrine\ModelListener;
use GetStream\Stream\Client;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class StreamServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    protected $provides = [
        FeedManagerInterface::class,
        EnrichInterface::class,
        Client::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->container->inflector(EntityManagerInterface::class, function (EntityManagerInterface $entityManager) {
            $listeners = [
                ModelListener::class,
            ];

            $entityListener = $entityManager->getConfiguration()->getEntityListenerResolver();

            foreach ($listeners as $listener) {
                $listenerObject = $this->container->get($listener);

                $entityListener->register($listenerObject);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(Client::class, function () {
            if (getenv('STREAM_URL')) {
                return Client::herokuConnect(getenv('STREAM_URL'));
            }

            return new Client(
                getenv('STREAM_APP_KEY'),
                getenv('STREAM_APP_SECRET')
            );
        });

        $this->container->add(FeedManagerInterface::class, function () {
            return new FeedManager(
                $this->container->get(Client::class)
            );
        });

        $this->container->add(EnrichInterface::class, function () {
            return new Enrich($this->container->get(EntityManagerInterface::class));
        });
    }
}
