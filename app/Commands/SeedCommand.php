<?php

namespace App\Commands;

use App\Models\Post;
use App\Models\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use Ramsey\Uuid\UuidFactoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedCommand extends Command
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
     * @var UuidFactoryInterface
     */
    private $uuid;

    /**
     * @var string
     */
    private $fixturesPath;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Generator $faker
     * @param UuidFactoryInterface $uuid
     * @param $fixturesPath
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Generator $faker,
        UuidFactoryInterface $uuid,
        $fixturesPath
    ) {
        $this->entityManager = $entityManager;
        $this->faker = $faker;
        $this->uuid = $uuid;
        $this->fixturesPath = $fixturesPath;

        parent::__construct();
    }

    /**
     *
     */
    public function configure()
    {
        $this->setName('db:seed');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $users = json_decode(file_get_contents($this->fixturesPath.'/users.json'), true);
        $images = json_decode(file_get_contents('https://picsum.photos/list'), true);

        $imageIds = array_map(function (array $image) {
            return $image['id'];
        }, $images);

        foreach ($users as $user) {
            $this->entityManager->persist(User::create(
                $this->uuid->uuid4(),
                $user['first_name'],
                $user['last_name'],
                $user['username'],
                $user['email'],
                'https://picsum.photos/200/200?image='.$this->faker->randomElement($imageIds),
                password_hash('secret', PASSWORD_DEFAULT)
            ));
        }

        foreach (range(1, 20) as $i) {
            $user = User::create(
                $this->uuid->uuid4(),
                $this->faker->firstName(),
                $this->faker->lastName(),
                $this->faker->userName(),
                $this->faker->email(),
                'https://picsum.photos/200/200?image='.$this->faker->randomElement($imageIds),
                password_hash('secret', PASSWORD_DEFAULT)
            );

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();

        $users = $this->entityManager->getRepository(User::class)->findAll();

        $listeners = $this->entityManager->getClassMetadata(Post::class)->entityListeners;
        //$this->entityManager->getClassMetadata(Post::class)->entityListeners = [];

        foreach (range(1, 100) as $i) {
            $post = Post::create(
                $this->uuid->uuid4(),
                $users[array_rand($users)],
                $this->faker->realText(140),
                DateTimeImmutable::createFromMutable($this->faker->dateTimeThisMonth())
            );

            $this->entityManager->persist($post);
        }

        $this->entityManager->flush();

        $this->entityManager->getClassMetadata(Post::class)->entityListeners = $listeners;
    }
}
