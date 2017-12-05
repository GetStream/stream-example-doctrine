<?php

namespace App\Users\Commands;

use Doctrine\ORM\EntityManagerInterface;
use GetStream\Doctrine\FeedManagerInterface;

class FollowHandler
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
     * @param Follow $command
     */
    public function handle(Follow $command)
    {
        $user = $command->getUser();
        $target = $command->getTarget();

        $feed = $this->feedManager->getFeed('timeline', $user->id());
        $feed->follow('timeline', $target->id());

        if ($user->follows()->contains($target)) {
            return;
        }

        $user->follows()->add($target);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
