<?php

namespace App\Users\Commands;

use Doctrine\ORM\EntityManagerInterface;
use GetStream\Doctrine\FeedManagerInterface;

class UnfollowHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FeedManagerInterface
     */
    private $feedManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param FeedManagerInterface $feedManager
     */
    public function __construct(EntityManagerInterface $entityManager, FeedManagerInterface $feedManager)
    {
        $this->entityManager = $entityManager;
        $this->feedManager = $feedManager;
    }

    /**
     * @param Unfollow $command
     */
    public function handle(Unfollow $command)
    {
        $user = $command->getUser();
        $target = $command->getTarget();

        $feed = $this->feedManager->getFeed('timeline', $user->id());
        $feed->unfollow('timeline', $target->id());

        if (!$user->follows()->contains($target)) {
            return;
        }

        $user->follows()->removeElement($target);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
