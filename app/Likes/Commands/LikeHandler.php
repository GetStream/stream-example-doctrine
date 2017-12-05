<?php

namespace App\Likes\Commands;

use App\Likes\Commands\Like as Command;
use App\Models\Like;
use Doctrine\ORM\EntityManagerInterface;

class LikeHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \App\Likes\Commands\Like $command
     */
    public function handle(Command $command)
    {
        $like = Like::create($command->getUser(), $command->getPost());

        $this->entityManager->persist($like);
        $this->entityManager->flush();
    }
}
