<?php

namespace App\Models;

use Cake\Chronos\Chronos;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Orm\Mapping as ORM;
use GetStream\Doctrine\ActivityInterface;
use GetStream\Doctrine\ActivityTrait;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="PostRepository")
 * @ORM\Table(name="posts")
 * @ORM\EntityListeners({"GetStream\Doctrine\ModelListener"})
 */
class Post implements ActivityInterface
{
    use ActivityTrait;

    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=280)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable", name="created_at")
     */
    private $createdAt;

    /**
     * Each post is created by someone.
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private $creator;

    /**
     * A Post has Many Likes.
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="Like", mappedBy="post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $likes;

    /**
     *
     */
    private function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    /**
     * @param UuidInterface $id
     * @param User $creator
     * @param string $content
     * @param DateTimeImmutable $createdAt
     *
     * @return static
     */
    public static function create(UuidInterface $id, User $creator, string $content, DateTimeImmutable $createdAt = null)
    {
        $post = new static();

        $post->id = $id;
        $post->creator = $creator;
        $post->content = $content;
        $post->createdAt = $createdAt ?: new DateTimeImmutable();

        return $post;
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function creator()
    {
        return $this->creator;
    }

    /**
     * @return \DateTimeInterface
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Collection
     */
    public function likes()
    {
        return $this->likes;
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    // GetStream Doctrine methods:

    /*
    /**
     * @return string
     */
    public function activityVerb()
    {
        return 'post';
    }

    /**
     * @return string
     */
    public function activityActorId()
    {
        return $this->creator->id();
    }

    /**
     * @return DateTimeInterface
     */
    public function activityTime()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function activityActor()
    {
        return User::class.':'.$this->activityActorId();
    }

    /**
     * @return mixed
     */
    protected function activityId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function activityNotify()
    {
        return ['timeline:'.$this->activityActorId()];
    }
}
