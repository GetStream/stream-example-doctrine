<?php

namespace App\Users\Commands;

use App\Models\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use Faker\Generator;

class SignupHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Generator $faker
     */
    public function __construct(EntityManagerInterface $entityManager, Generator $faker)
    {
        $this->entityManager = $entityManager;
        $this->faker = $faker;
    }

    /**
     * @param Signup $command
     */
    public function handle(Signup $command)
    {
        $images = json_decode(file_get_contents('https://picsum.photos/list'), true);

        $imageIds = array_map(function (array $image) {
            return $image['id'];
        }, $images);

        $user = User::create(
            $command->getId(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getUsername(),
            $command->getEmail(),
            'https://picsum.photos/200/200?image='.$this->faker->randomElement($imageIds),
            password_hash($command->getPassword(), PASSWORD_DEFAULT)
        );

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new DomainException('Values need to be unique.', 0, $exception);
        }
    }
}
