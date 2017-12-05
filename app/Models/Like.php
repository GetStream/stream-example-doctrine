<?php

namespace App\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use GetStream\Doctrine\ActivityInterface;
use GetStream\Doctrine\ActivityTrait;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="LikeRepository")
 * @ORM\Table(name="likes", uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *         name="like_unique",
 *         columns={"user_id", "post_id"})
 *     }
 * )
 * @ORM\EntityListeners({"GetStream\Doctrine\ModelListener"})
 */
class Like implements ActivityInterface
{
    use ActivityTrait;

    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime_immutable", name="created_at")
     */
    private $createdAt;

    /**
     * @param User $user
     * @param Post $post
     *
     * @return static
     */
    public static function create(User $user, Post $post)
    {
        $like = new static();

        $like->user = $user;
        $like->post = $post;
        $like->createdAt = new DateTimeImmutable();

        return $like;
    }

    /**
     * @return UuidInterface
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return Post
     */
    public function post()
    {
        return $this->post;
    }

    /**
     * @return \DateTimeInterface
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'user') {
            return $this->user();
        }

        parent::__get($name);
    }

    /**
     * @return UuidInterface
     */
    protected function activityId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function activityActorId()
    {
        return $this->user->id();
    }

    /**
     * @return string
     */
    public function activityActor()
    {
        return User::class.':'.$this->activityActorId();
    }

    /**
     * @return string
     */
    public function activityVerb()
    {
        return 'like';
    }

    /**
     * @return DateTimeImmutable
     */
    public function activityTime()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function activityObject()
    {
        return Post::class .':'. $this->post()->id();
    }

    /**
     * @return string
     */
    public function activityForeignId()
    {
        return static::class .':'. $this->id();
    }
}
