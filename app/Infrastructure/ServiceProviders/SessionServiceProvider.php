<?php

namespace App\Infrastructure\ServiceProviders;

use App\Infrastructure\Middleware\CallableWrapper;
use Dflydev\FigCookies\SetCookie;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Time\SystemCurrentTime;
use Zend\Diactoros\Response;

class SessionServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        SessionMiddleware::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(SessionMiddleware::class, function () {
            $middleware = new SessionMiddleware(
                new Sha256(),
                getenv('SESSION_SECRET'),
                getenv('SESSION_SECRET'),
                SetCookie::create('session')->withHttpOnly(true)->withPath('/'),
                new Parser(),
                3600,
                new SystemCurrentTime()
            );

            return new CallableWrapper($middleware, new Response());
        });
    }
}
