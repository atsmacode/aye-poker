<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:devpw',
    description: 'Use the pre-generated password and populate .env.',
)]
class SetDockerPassword extends Command
{
    public function __construct(private string $appEnv)
    {
        parent::__construct('app:devpw');
    }

    protected function configure(): void
    {
        $this->setHelp('Use the pre-generated password and populate .env. For dev use only.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->appEnv != 'dev') {
            $output->writeln("This command is for dev use only.");

            return Command::SUCCESS;
        }

        $filesystem = new Filesystem();
        $password = file_get_contents('db_root_password.txt');

        try {
            $output->writeln("Populating .env with Docker password");
            $template = file_get_contents('misc/templates/docker_db_password.txt');

            $filesystem->appendToFile('.env', sprintf("$template", trim($password)));
        } catch (\Exception $e) {
            $output->writeln("Failed to populate .env: {$e->getMessage()}");

            return Command::FAILURE;
        }

        $output->writeln("<info>Successfully added Docker password to .env</info>");

        return Command::SUCCESS;
    }
}
