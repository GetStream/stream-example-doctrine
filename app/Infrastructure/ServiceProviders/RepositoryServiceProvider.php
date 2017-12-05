<?php

namespace App\Infrastructure\ServiceProviders;

use App\Models\Post;
use App\Models\PostRepository;
use App\Models\User;
use App\Models\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class RepositoryServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        UserRepository::class,
        PostRepository::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->add(UserRepository::class, function () {
            return $this->container->get(EntityManagerInterface::class)->getRepository(User::class);
        });

        $this->container->add(PostRepository::class, function () {
            return $this->container->get(EntityManagerInterface::class)->getRepository(Post::class);
        });
    }
}
