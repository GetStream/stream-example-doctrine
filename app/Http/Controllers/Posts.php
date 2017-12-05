<?php

namespace App\Http\Controllers;

use App\Likes\Commands\Like;
use App\Models\Post;
use App\Posts\Commands\Create;
use Doctrine\ORM\EntityManagerInterface;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\UuidFactoryInterface;
use Zend\Diactoros\Response\RedirectResponse;

class Posts
{
    /**
     * @var UuidFactoryInterface
     */
    private $uuid;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @param UuidFactoryInterface $uuid
     * @param EntityManagerInterface $entityManager
     * @param CommandBus $commandBus
     */
    public function __construct(
        UuidFactoryInterface $uuid,
        EntityManagerInterface $entityManager,
        CommandBus $commandBus
    ) {
        $this->uuid = $uuid;
        $this->entityManager = $entityManager;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request, ResponseInterface $response)
    {
        $post = $request->getParsedBody()['post'];
        $user = $request->getAttribute('user');

        $uuid = $this->uuid->uuid4();

        $this->commandBus->handle(new Create($uuid, $user, $post));

        return new RedirectResponse('/');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function like(ServerRequestInterface $request, ResponseInterface $response)
    {
        $user = $request->getAttribute('user');
        $postId = $request->getParsedBody()['post_id'];

        $post = $this->entityManager->find(Post::class, $postId);

        $this->commandBus->handle(new Like($user, $post));

        return new RedirectResponse($request->getHeaderLine('Referer'));
    }
}
