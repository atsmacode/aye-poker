<?php

namespace Atsmacode\Framework\Database;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class Database
{
    protected mixed $connection;
    protected string $database;
    protected LoggerInterface $logger;
    protected ContainerInterface $container;

    public function __construct(ConnectionInterface $connection, LoggerInterface $logger, ContainerInterface $container)
    {
        $this->connection = $connection->getConnection();
        $this->database = $connection->getDatabaseName();
        $this->logger = $logger;
        $this->container = $container;
    }
}
