<?php

require __DIR__.'/../vendor/autoload.php';

exit(call_user_func(function () {
    $whoops = new Whoops\Run();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
    $whoops->register();

    $container = App\Infrastructure\ContainerFactory::create(__DIR__.'/..');
    $kernel = new App\Http\Kernel($container);

    $request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

    $response = $kernel($request, new Zend\Diactoros\Response());

    (new Zend\Diactoros\Response\SapiEmitter())->emit($response);
}));

