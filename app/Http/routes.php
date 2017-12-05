<?php

/** @var \League\Route\RouteCollection $router */
use App\Http\Controllers;
use App\Http\Middleware;
use League\Route\RouteGroup;

$router->group('/', function (RouteGroup $router) {
    $router->get('/login', Controllers\Login::class.'::show');
    $router->post('/login', Controllers\Login::class.'::login');
    $router->get('/login/demo', Controllers\Login::class.'::demo');
    $router->get('/signup', Controllers\Signup::class.'::show');
    $router->post('/signup', Controllers\Signup::class.'::signup');
})->middleware($this->container->get(Middleware\GuestMiddleware::class));

$router->group('/', function (RouteGroup $router) {
    $router->get('/', Controllers\Index::class.'::home');
    $router->get('/logout', Controllers\Login::class.'::logout');
    $router->get('/profiles', Controllers\Profile::class.'::index');
    $router->post('/posts', Controllers\Posts::class.'::create');
    $router->post('/follows', Controllers\Follows::class.'::follow');
    $router->delete('/follows', Controllers\Follows::class.'::unfollow');
    $router->post('/likes', Controllers\Likes::class.'::like');
    $router->delete('/likes', Controllers\Likes::class.'::unlike');
    $router->get('/{username}', Controllers\Profile::class.'::show');
})->middleware($this->container->get(Middleware\AuthorizationMiddleware::class));
