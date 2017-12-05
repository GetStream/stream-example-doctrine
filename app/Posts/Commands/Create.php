<?php

namespace App\Posts\Commands;

use App\Models\User;
use Ramsey\Uuid\UuidInterface;

class Create
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var User
     */
    private $creator;

    /**
     * @var string
     */
    private $content;

    /**
     * @param UuidInterface $id
     * @param User $creator
     * @param string $content
     */
    public function __construct(UuidInterface $id, User $creator, $content)
    {
        $this->id = $id;
        $this->creator = $creator;
        $this->content = $content;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
