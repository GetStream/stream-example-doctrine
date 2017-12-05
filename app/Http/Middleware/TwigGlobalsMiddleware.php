<?php

namespace App\Http\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig_Environment;

class TwigGlobalsMiddleware implements ServerMiddlewareInterface
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if ($user = $request->getAttribute('user')) {
            $this->twig->addGlobal('user', $user);
        }

        $this->twig->addGlobal('path', $request->getUri()->getPath());

        return $delegate->process($request);
    }
}
