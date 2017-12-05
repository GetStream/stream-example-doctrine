<?php

namespace App\Infrastructure\ServiceProviders;

use App\Infrastructure\Twig\TimeExtension;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ViewServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        Twig_Environment::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->container->share(Twig_Environment::class, function () {
            $path = $this->container->get('base_path').'/resources/views/';

            $twig = new Twig_Environment(new Twig_Loader_Filesystem([$path]));

            $twig->addExtension($this->container->get(TimeExtension::class));

            return $twig;
        });
    }
}
