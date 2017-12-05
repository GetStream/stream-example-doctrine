<?php

namespace App\Http\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use League\Route\RouteCollection;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class RouterMiddleware implements ServerMiddlewareInterface
{
    /**
     * @var RouteCollection
     */
    private $router;

    /**
     * @param RouteCollection $router
     */
    public function __construct(RouteCollection $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        return $this->router->dispatch($request, new Response());
    }
}
