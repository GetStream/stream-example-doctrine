<?php

namespace App\Infrastructure\Middleware;

use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CallableInteropMiddleware
{
    /**
     * @var ServerMiddlewareInterface
     */
    private $middleware;

    /**
     * @param ServerMiddlewareInterface $middleware
     */
    public function __construct(ServerMiddlewareInterface $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        return $this->middleware->process($request, new CallableWrapperDelegate($next, $response));
    }
}
