<?php

namespace Atsmacode\Framework\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class DbalConnection implements ConnectionInterface
{
    private string $database;
    private Connection $connection;

    public function __construct(array $config, string $env)
    {
        $this->database = $config['db'][$env]['database'];
        $this->connection = DriverManager::getConnection([
            'dbname' => $config['db'][$env]['database'],
            'user' => $config['db'][$env]['username'],
            'password' => $config['db'][$env]['password'],
            'host' => $config['db'][$env]['servername'],
            'driver' => $config['db'][$env]['driver'],
        ]);
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function getDatabaseName(): string
    {
        return $this->database;
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function rollback(): void
    {
        $this->connection->rollback();
    }
}
