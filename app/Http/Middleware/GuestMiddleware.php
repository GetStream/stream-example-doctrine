<?php

namespace App\Http\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class GuestMiddleware implements ServerMiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if ($request->getAttribute('user')) {
            return new RedirectResponse($request->getUri()->withPath('/'));
        }

        return $delegate->process($request);
    }
}
