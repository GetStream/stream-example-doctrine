<?php

namespace App\Likes\Commands;

use App\Likes\Commands\Unlike as Command;
use App\Models\Like;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class UnlikeHandler
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
     * @param \App\Likes\Commands\Unlike $command
     */
    public function handle(Command $command)
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('user', $command->getUser()))
            ->andWhere(Criteria::expr()->eq('post', $command->getPost()));

        $likes = $this->entityManager->getRepository(Like::class)->matching($criteria);

        foreach ($likes as $like) {
            $this->entityManager->remove($like);
        }

        $this->entityManager->flush();
        $this->entityManager->flush();
    }
}
