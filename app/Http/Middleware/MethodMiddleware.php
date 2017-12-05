<?php

namespace App\Http\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

class MethodMiddleware implements ServerMiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if ($request->getMethod() === 'POST' && isset($request->getParsedBody()['_method'])) {
            $request = $request->withMethod(mb_strtoupper($request->getParsedBody()['_method']));
        }

        return $delegate->process($request);
    }
}
