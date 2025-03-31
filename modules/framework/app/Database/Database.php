<?php

namespace Atsmacode\Framework\Database;

use Psr\Log\LoggerInterface;

class Database
{
    protected mixed $connection;
    protected string $database;
    protected LoggerInterface $logger;

    public function __construct(ConnectionInterface $connection, LoggerInterface $logger)
    {
        $this->connection = $connection->getConnection();
        $this->database = $connection->getDatabaseName();
        $this->logger = $logger;
    }
}
