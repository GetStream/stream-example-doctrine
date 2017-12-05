<?php

namespace App\Infrastructure\ServiceProviders;

use App\Http\Middleware\AuthorizationMiddleware;
use App\Http\Middleware\GuestMiddleware;
use App\Infrastructure\Middleware\CallableInteropMiddleware;
use League\Container\ServiceProvider\AbstractServiceProvider;

class MiddlewareServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        AuthorizationMiddleware::class,
        GuestMiddleware::class,
    ];
    
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(AuthorizationMiddleware::class, function () {
            return new CallableInteropMiddleware(new AuthorizationMiddleware());
        });

        $this->container->share(GuestMiddleware::class, function () {
            return new CallableInteropMiddleware(new GuestMiddleware());
        });
    }
}
