<?php

namespace App\Infrastructure\ServiceProviders;

use App\Likes\Commands\Like;
use App\Likes\Commands\LikeHandler;
use App\Likes\Commands\Unlike;
use App\Likes\Commands\UnlikeHandler;
use App\Posts\Commands\Create;
use App\Posts\Commands\CreateHandler;
use App\Users\Commands\Follow;
use App\Users\Commands\FollowHandler;
use App\Users\Commands\Signup;
use App\Users\Commands\SignupHandler;
use App\Users\Commands\Unfollow;
use App\Users\Commands\UnfollowHandler;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;

class CommandBusServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        CommandBus::class,
    ];

    /**
     * @var array
     */
    private $commands = [
        Like::class => LikeHandler::class,
        Unlike::class => UnlikeHandler::class,
        Follow::class => FollowHandler::class,
        Unfollow::class => UnfollowHandler::class,
        Create::class => CreateHandler::class,
        Signup::class => SignupHandler::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(CommandBus::class, function () {
            $handlerMiddleware = new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                new ContainerLocator($this->container, $this->commands),
                new HandleInflector()
            );

            return new CommandBus([$handlerMiddleware]);
        });
    }
}
