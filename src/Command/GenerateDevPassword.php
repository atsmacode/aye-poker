<?php

namespace App\Command;

use App\Build\BuildAyePoker;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:devpw',
    description: 'Generate a random DB password for dev use',
)]
class GenerateDevPassword extends Command
{
    public function __construct()
    {
        parent::__construct('app:devpw');
    }

    protected function configure(): void
    {
        $this->setHelp('This will generate a random password and populate .env and Docker secret files. For dev use only.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();

        $password = file_get_contents('db_root_password.txt');

        try {
            $output->writeln("Populating .env");

            $filesystem->appendToFile('.env', sprintf('
DOCKER_DB_PASSWORD="%s"
            ', $password));
        } catch (\Exception $e) {
            $output->writeln("Failed to populate .env: {$e->getMessage()}");

            return Command::FAILURE;
        }

        $output->writeln("<info>Successfully populated .env</info>");

        return Command::SUCCESS;
    }
}
