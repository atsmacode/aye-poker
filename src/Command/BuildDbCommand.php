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
    name: 'app:builddb',
    description: 'Builds the Poker Game DB and sets up required config.',
)]
class BuildDbCommand extends Command
{
    protected function configure(): void
    {
        //
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $filesystem = new Filesystem();

        $qSymfonyName = new Question(
            'Please enter the database name for the Symfony application (press enter to use default \'aye_poker\'): ',
            'aye_poker'
        );
        $qSymfonyUser = new Question('Please enter the database username for the Symfony application: ');
        $qSymfonyPass = new Question('Please enter the database password for the Symfony application: ');
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
                    'DATABASE_URL="mysql://%s:%s@127.0.0.1:3306/%s?serverVersion=8&charset=utf8mb4"',
                    $symfonyUser,
                    $symfonyPass,
                    $symfonyName
                )
            );
        } catch (IOExceptionInterface $e) {
            $output->writeln("Failed to populate .env: " . $e->getMessage());

            return Command::FAILURE;
        }

        $output->writeln("Successfully populated .env");

        $qPokerGameName = new Question(
            'Please enter the database name for the Poker Game application (press enter to use default \'poker_game\'): ',
            'poker_game'
        );
        $qPokerGameUser = new Question('Please enter the database username for the Poker Game application: ');
        $qPokerGamePass = new Question('Please enter the database password for the Poker Game application: ');
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
            } catch (IOExceptionInterface $e) {
                $output->writeln("An error occurred while creating {$pokerConfigPath} " . $e->getMessage());

                return Command::FAILURE;
            }

            $output->writeln("Successfully created {$pokerConfigPath}");
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
            $output->writeln('Failed to build Poker Game DB: ' . $e->getMessage());

            return Command::FAILURE;
        }

        $output->writeln('Successfully built Poker Game DB');

        return Command::SUCCESS;
    }

    private function getPokerConfigFormat(string $database, string $username, string $password)
    {
        return sprintf('<?php

            return [
                \'poker_game\' => [
                    \'db\' => [
                        \'live\' => [
                            \'servername\' => \'localhost\',
                            \'username\'   => \'%2$s\',
                            \'password\'   => \'%3$s\',
                            \'database\'   => \'%1$s\',
                            \'driver\'     => \'pdo_mysql\',
                        ],
                        \'test\' => [
                            \'servername\' => \'localhost\',
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
