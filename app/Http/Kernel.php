<?php

namespace App\Http;

use App\Http\Middleware\MethodMiddleware;
use App\Http\Middleware\RouterMiddleware;
use App\Http\Middleware\TwigGlobalsMiddleware;
use App\Http\Middleware\UserMiddleware;
use mindplay\middleman\ContainerResolver;
use mindplay\middleman\Dispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;

class Kernel
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $middleware = [
            MethodMiddleware::class,
            SessionMiddleware::class,
            UserMiddleware::class,
            TwigGlobalsMiddleware::class,
            RouterMiddleware::class,
        ];

        $dispatcher = new Dispatcher($middleware,
            new ContainerResolver($this->container)
        );

        return $dispatcher->dispatch($request);
    }
}
