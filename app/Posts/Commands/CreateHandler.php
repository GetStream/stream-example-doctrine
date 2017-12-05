<?php

namespace App\Posts\Commands;

use App\Models\Post;
use Doctrine\ORM\EntityManagerInterface;

class CreateHandler
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
     * @param Create $command
     */
    public function handle(Create $command)
    {
        $post = Post::create($command->getId(), $command->getCreator(), $command->getContent());

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}
