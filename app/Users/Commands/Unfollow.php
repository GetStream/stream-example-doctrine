<?php

namespace App\Users\Commands;

use App\Models\User;

class Unfollow
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var User
     */
    private $target;

    /**
     * @param User $user
     * @param User $target
     */
    public function __construct(User $user, User $target)
    {
        $this->user = $user;
        $this->target = $target;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return User
     */
    public function getTarget(): User
    {
        return $this->target;
    }
}
