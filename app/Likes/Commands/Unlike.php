<?php

namespace App\Likes\Commands;

use App\Models\Post;
use App\Models\User;

class Unlike
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Post
     */
    private $post;

    /**
     * @param User $user
     * @param Post $post
     */
    public function __construct(User $user, Post $post)
    {

        $this->user = $user;
        $this->post = $post;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }
}
