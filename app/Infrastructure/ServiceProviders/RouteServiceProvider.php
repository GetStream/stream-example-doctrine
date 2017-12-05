<?php

namespace App\Infrastructure\ServiceProviders;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;

class RouteServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        RouteCollection::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(RouteCollection::class, function () {
            $router = new RouteCollection($this->container);
            //$router->setStrategy(new ExceptionStrategy());

            require($this->container->get('base_path').'/app/Http/routes.php');

            return $router;
        });
    }
}
