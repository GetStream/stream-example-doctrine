<?php

namespace App\Http\Controllers;

use App\Likes\Commands\Like;
use App\Likes\Commands\Unlike;
use App\Models\Post;
use Doctrine\ORM\EntityManagerInterface;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class Likes
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @param EntityManagerInterface $entityManager
     * @param CommandBus $commandBus
     */
    public function __construct(EntityManagerInterface $entityManager, CommandBus $commandBus)
    {
        $this->entityManager = $entityManager;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function like(ServerRequestInterface $request, ResponseInterface $response)
    {
        $postId = $request->getParsedBody()['post_id'];
        $post = $this->entityManager->find(Post::class, $postId);

        $this->commandBus->handle(new Like($request->getAttribute('user'), $post));

        return new RedirectResponse($request->getHeaderLine('Referer'));
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function unlike(ServerRequestInterface $request, ResponseInterface $response)
    {
        $postId = $request->getParsedBody()['post_id'];
        $post = $this->entityManager->find(Post::class, $postId);

        $this->commandBus->handle(new Unlike($request->getAttribute('user'), $post));

        return new RedirectResponse($request->getHeaderLine('Referer'));
    }
}
