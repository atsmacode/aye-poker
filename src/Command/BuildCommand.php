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
    name: 'app:build',
    description: 'Builds the Poker Game DB and sets up required config',
)]
class BuildCommand extends Command
{
    protected function configure(): void
    {
        $this->setHelp('This will run the migrations for the Poker Game app and create config/poker_game.php & .env files for you.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $filesystem = new Filesystem();

        $qSymfonyName = new Question(
            '<question>Please enter the database name for the Symfony application (press enter to use default \'aye_poker\'):</question> ',
            'aye_poker'
        );
        $qSymfonyUser = new Question('<question>Please enter the database username for the Symfony application:</question> ');
        $qSymfonyPass = new Question('<question>Please enter the database password for the Symfony application:</question> ');
        $qSymfonyPass->setHidden(true);

        $symfonyName = $helper->ask($input, $output, $qSymfonyName);
        $symfonyUser = $helper->ask($input, $output, $qSymfonyUser);
        $symfonyPass = $helper->ask($input, $output, $qSymfonyPass);

        try {
            $output->writeln("Populating .env");

            $filesystem->copy('.env.template', '.env', true);

            $filesystem->appendToFile(
                '.env',
                sprintf(
                    'DATABASE_URL="mysql://%s:%s@db:3306/%s?serverVersion=8&charset=utf8mb4"',
                    $symfonyUser,
                    $symfonyPass,
                    $symfonyName
                )
            );
        } catch (\Exception $e) {
            $output->writeln("Failed to populate .env: {$e->getMessage()}");

            return Command::FAILURE;
        }

        $output->writeln("<info>Successfully populated .env</info>");

        $qPokerGameName = new Question(
            '<question>Please enter the database name for the Poker Game application (press enter to use default \'poker_game\'):</question> ',
            'poker_game'
        );
        $qPokerGameUser = new Question('<question>Please enter the database username for the Poker Game application:</question> ');
        $qPokerGamePass = new Question('<question>Please enter the database password for the Poker Game application:</question> ');
        $qPokerGamePass->setHidden(true);

        $pokerName = $helper->ask($input, $output, $qPokerGameName);
        $pokerUser = $helper->ask($input, $output, $qPokerGameUser);
        $pokerPass = $helper->ask($input, $output, $qPokerGamePass);

        if (!$filesystem->exists('config/poker_game.php')) {
            $pokerConfigPath = 'config/poker_game.php';
            $output->writeln("Creating {$pokerConfigPath}");

            try {
                $filesystem->dumpFile(
                    'config/poker_game.php',
                    $this->getPokerConfigFormat($pokerName, $pokerUser, $pokerPass)
                );
            } catch (\Exception $e) {
                $output->writeln("<error>An error occurred while creating {$pokerConfigPath}: {$e->getMessage()}</error>");

                return Command::FAILURE;
            }

            $output->writeln("<info>Successfully created {$pokerConfigPath}</info>");
        }

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

        return Command::SUCCESS;
    }

    private function getPokerConfigFormat(string $database, string $username, string $password)
    {
        return sprintf('<?php

            return [
                \'poker_game\' => [
                    \'db\' => [
                        \'live\' => [
                            \'servername\' => \'db\',
                            \'username\'   => \'%2$s\',
                            \'password\'   => \'%3$s\',
                            \'database\'   => \'%1$s\',
                            \'driver\'     => \'pdo_mysql\',
                        ],
                        \'test\' => [
                            \'servername\' => \'db\',
                            \'username\'   => \'%2$s\',
                            \'password\'   => \'%3$s\',
                            \'database\'   => \'%1$s_test\',
                            \'driver\'     => \'pdo_mysql\',
                        ],
                    ],
                    \'logger\' => [
                        \'path\' => \'/your/log/file\'
                    ]
                ],
            ];', $database, $username, $password);

    }
}
