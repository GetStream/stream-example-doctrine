<?php

namespace App\Infrastructure\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CallableWrapper implements ServerMiddlewareInterface
{
    /**
     * @var callable
     */
    private $middleware;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param callable $middleware
     * @param ResponseInterface $response
     */
    public function __construct(callable $middleware, ResponseInterface $response)
    {
        $this->middleware = $middleware;
        $this->response = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        return call_user_func_array($this->middleware, [$request, $this->response, [$delegate, 'process']]);
    }
}
