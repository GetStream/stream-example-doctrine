<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Users\Commands\Follow;
use App\Users\Commands\Unfollow;
use Doctrine\ORM\EntityManagerInterface;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class Follows
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param CommandBus $commandBus
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CommandBus $commandBus, EntityManagerInterface $entityManager)
    {
        $this->commandBus = $commandBus;
        $this->entityManager = $entityManager;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function follow(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var User $user */
        $user = $request->getAttribute('user');

        // Get second (after 2nd slash) segment from route.
        $targetId = $request->getParsedBody()['target_id'];

        $target = $this->entityManager->find(User::class, $targetId);

        $this->commandBus->handle(new Follow($user, $target));

        return new RedirectResponse($request->getHeaderLine('Referer').'#'.$targetId);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function unfollow(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var User $user */
        $user = $request->getAttribute('user');

        // Get second (after 2nd slash) segment from route.
        $targetId = $request->getParsedBody()['target_id'];

        $target = $this->entityManager->find(User::class, $targetId);

        $this->commandBus->handle(new Unfollow($user, $target));

        return new RedirectResponse($request->getHeaderLine('Referer').'#'.$targetId);
    }
}
