<?php

namespace App\Infrastructure\ServiceProviders;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class LogServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * @var array
     */
    protected $provides = [
        LoggerInterface::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(LoggerInterface::class, function () {
            $logger = new Logger('App');

            if (getenv('DYNO')) {
                $logger->pushHandler(new StreamHandler('php://stderr'));
            } else {
                $path = $this->container->get('base_path');
                $logger->pushHandler(new RotatingFileHandler($path.'/storage/logs/app.log'));
            }

            return $logger;
        });
    }

    /**
     * Method will be invoked on registration of a service provider implementing
     * this interface. Provides ability for eager loading of Service Providers.
     *
     * @return void
     */
    public function boot()
    {
        $this->container->inflector(LoggerAwareInterface::class, function (LoggerAwareInterface $object) {
            $object->setLogger($this->container->get(LoggerInterface::class));
        });
    }
}
