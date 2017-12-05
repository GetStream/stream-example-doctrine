<?php

namespace App\Http\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class AuthorizationMiddleware implements ServerMiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $user = $request->getAttribute('user');

        if (is_null($user)) {
            return new RedirectResponse($request->getUri()->withPath('/login'));
        }

        return $delegate->process($request);
    }
}
