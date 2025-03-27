<?php

namespace App\Command;

use App\Build\BuildAyePoker;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:build',
    description: 'Builds the Poker Game DB and sets up required config',
)]
class BuildCommand extends Command
{
    public function __construct(private string $dockerDbPw)
    {
        parent::__construct('app:build');
    }

    protected function configure(): void
    {
        $this->setHelp('This will run the migrations for the Poker Game app, create config/poker_game.php and update .env files for you.');

        $this->addOption('docker', 'd', InputOption::VALUE_NONE, 'Use Docker credentials');
        $this->addOption('no-migration', 'm', InputOption::VALUE_NONE, 'Do not run the migrations');
        $this->addOption('no-config', 'c', InputOption::VALUE_NONE, 'Do not set required config');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $docker = $input->getOption('docker');
        $noMigrate = $input->getOption('no-migration');
        $noConfig = $input->getOption('no-config');
        $helper = $this->getHelper('question');

        $filesystem = new Filesystem();

        $qSymfonyName = new Question(
            '<question>Please enter the database name for the Symfony application (press enter to use default \'aye_poker\'):</question> ',
            'aye_poker'
        );
        $qSymfonyUser = new Question('<question>Please enter the database username for the Symfony application:</question> ');
        $qSymfonyPass = new Question('<question>Please enter the database password for the Symfony application:</question> ');
        $qSymfonyPass->setHidden(true);

        $symfonyName = $docker ? 'aye_poker' : $helper->ask($input, $output, $qSymfonyName);
        $symfonyUser = $docker ? 'root' : $helper->ask($input, $output, $qSymfonyUser);
        $symfonyPass = $docker ? $this->dockerDbPw : $helper->ask($input, $output, $qSymfonyPass);
        $symfonyHost = $docker ? 'db' : 'localhost';

        if (! $noConfig) {
            try {
                $output->writeln("Populating .env with DB credentials");
                $template = file_get_contents('misc/templates/database_url.txt');
    
                $filesystem->appendToFile('.env', sprintf(
                        $template,
                        $symfonyUser,
                        $symfonyPass,
                        $symfonyHost,
                        $symfonyName
                    )
                );
            } catch (\Exception $e) {
                $output->writeln("Failed to populate .env: {$e->getMessage()}");
    
                return Command::FAILURE;
            }

            $output->writeln("<info>Successfully populated .env</info>");
        }

        $qPokerGameName = new Question(
            '<question>Please enter the database name for the Poker Game application (press enter to use default \'poker_game\'):</question> ',
            'poker_game'
        );
        $qPokerGameUser = new Question('<question>Please enter the database username for the Poker Game application:</question> ');
        $qPokerGamePass = new Question('<question>Please enter the database password for the Poker Game application:</question> ');
        $qPokerGamePass->setHidden(true);

        $pokerName = $docker ? 'poker_game' : $helper->ask($input, $output, $qPokerGameName);
        $pokerUser = $docker ? 'root' : $helper->ask($input, $output, $qPokerGameUser);
        $pokerPass = $docker ? $this->dockerDbPw : $helper->ask($input, $output, $qPokerGamePass);
        $pokerHost = $docker ? 'db' : 'localhost';

        if (! $noConfig) {
            $pokerConfigPath = 'config/poker_game.php';
            $output->writeln("Creating {$pokerConfigPath}");

            $template = file_get_contents('misc/templates/poker_game_config.txt');

            try {
                $filesystem->dumpFile(
                    'config/poker_game.php',
                    sprintf($template, $pokerName, $pokerUser, $pokerPass, $pokerHost)
                );
            } catch (\Exception $e) {
                $output->writeln("<error>An error occurred while creating {$pokerConfigPath}: {$e->getMessage()}</error>");

                return Command::FAILURE;
            }

            $output->writeln("<info>Successfully created {$pokerConfigPath}</info>");
        }

        if (! $noMigrate) {
            try {
                $output->writeln("Building poker_game DB");
    
                $app            = (new BuildAyePoker())->getApp();
                $createDb       = new ArrayInput(['command' => 'app:create-database']);
                $buildCardGames = new ArrayInput(['command' => 'app:build-card-games']);
                $buildPokerGame = new ArrayInput(['command' => 'app:build-poker-game']);
        
                $app->doRun($createDb, $output);
                $app->doRun($buildCardGames, $output);
                $app->doRun($buildPokerGame, $output);
            } catch (\Exception $e) {
                $output->writeln("<error>Failed to build Poker Game DB: {$e->getMessage()}</error>");
    
                return Command::FAILURE;
            }
    
            $output->writeln('<info>Successfully built Poker Game DB</info>');
        }

        return Command::SUCCESS;
    }
}
