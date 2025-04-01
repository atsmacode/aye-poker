<?php

namespace Atsmacode\Framework\Console\Commands;

use Atsmacode\Framework\Database\ConnectionInterface;
use Atsmacode\Framework\Migrations\CreateDatabase;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceManager;
use PDO;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Migrator extends Command
{
    protected array $buildClasses;

    /**
     * @var string
     */
    protected static $defaultName;

    public function __construct(
        ?string $name,
        private ServiceManager $container,
        private FactoryInterface $testDbFactory,
        private FactoryInterface $legacyTestDbFactory,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument('-v', InputArgument::OPTIONAL, 'Display feedback message in console.');
        $this->addOption('-d', '-d', InputArgument::OPTIONAL, 'Run in dev mode for running unit tests.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dev = 'true' === $input->getOption('-d') ?: false;

        if ($dev) {
            $this->container->setFactory(ConnectionInterface::class, new $this->testDbFactory());
        }

        $connection = $this->container->get(ConnectionInterface::class);
        $logger = $this->container->get(LoggerInterface::class);

        foreach ($this->buildClasses as $class) {
            if (CreateDatabase::class === $class) {
                /* @todo Using PDO for drop/create, doctrine always requires a DB name for a connection */

                if ($dev) {
                    $this->container->setFactory(\PDO::class, new $this->legacyTestDbFactory());
                }

                (new CreateDatabase($this->container->get(\PDO::class), $logger))
                    ->dropDatabase()
                    ->createDatabase();

                continue;
            }

            foreach ($class::$methods as $method) {
                (new $class($connection, $logger))->{$method}();
            }
        }

        return Command::SUCCESS;
    }
}
