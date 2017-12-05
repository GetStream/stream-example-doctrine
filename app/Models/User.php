<?php

namespace App\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Orm\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="first_name")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", name="last_name")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="avatar_url")
     */
    private $avatarUrl;

    /**
     * @ORM\Column(type="string", name="password")
     */
    private $passwordHash;

    /**
     * A User has Many Posts.
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="Post", mappedBy="creator")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $posts;

    /**
     * Many Users have Many Users.
     *
     * @var Collection
     * @ORM\ManyToMany(targetEntity="User", mappedBy="follows")
     */
    private $followers;

    /**
     * Many Users have many Users.
     *
     * @var Collection
     * @ORM\ManyToMany(targetEntity="User", inversedBy="followers")
     * @ORM\JoinTable(name="follows",
     *      joinColumns={@ORM\JoinColumn(name="follower_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="followee_id", referencedColumnName="id")}
     * )
     */
    private $follows;

    /**
     *
     */
    private function __construct()
    {
        $this->follows = new ArrayCollection();
        $this->followers = new ArrayCollection();
    }

    /**
     * @param UuidInterface $id
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $email
     * @param string $passwordHash
     * @param string $avatarUrl
     *
     * @return static
     */
    public static function create
    (
        UuidInterface $id,
        string $firstName,
        string $lastName,
        string $username,
        string $email,
        string $avatarUrl,
        string $passwordHash
    ) {
        $user = new static();

        $user->id = $id;
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->username = $username;
        $user->email = $email;
        $user->passwordHash = $passwordHash;
        $user->avatarUrl = $avatarUrl;

        return $user;
    }

    /**
     * @return UuidInterface
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->firstName .' '. $this->lastName;
    }

    /**
     * @return string
     */
    public function firstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function lastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function passwordHash()
    {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function avatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * @return Collection
     */
    public function posts()
    {
        return $this->posts;
    }

    /**
     * @return Collection
     */
    public function followers()
    {
        return $this->followers;
    }

    /**
     * @return Collection
     */
    public function follows()
    {
        return $this->follows;
    }
}
