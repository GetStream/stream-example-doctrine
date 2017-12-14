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
        $content = mb_substr($command->getContent(), 0, 280);
        $post = Post::create($command->getId(), $command->getCreator(), $content);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}
