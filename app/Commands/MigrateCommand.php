<?php

namespace App\Commands;

use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    /**
     * @var CreateCommand
     */
    private $doctrineCommand;

    /**
     * @var EntityManagerHelper
     */
    private $entityManagerHelper;

    /**
     * @param CreateCommand $doctrineCommand
     * @param EntityManagerHelper $entityManagerHelper
     */
    public function __construct(
        CreateCommand $doctrineCommand,
        EntityManagerHelper $entityManagerHelper
    ) {
        $this->doctrineCommand = $doctrineCommand;
        $this->entityManagerHelper = $entityManagerHelper;

        parent::__construct();
    }

    /**
     *
     */
    public function configure()
    {
        $this->setName('db:migrate');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     *
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getApplication();

        $app->add($this->doctrineCommand);
        $app->getHelperSet()->set($this->entityManagerHelper, 'em');
        $app->run(new ArrayInput(['command' => 'o:s:c']), $output);
    }
}
