<?php

namespace Atsmacode\Framework\Pdo;

use Atsmacode\Framework\Database\ConnectionInterface;

class PdoConnection implements ConnectionInterface
{
    private string $database;

    private \PDO $connection;

    public function __construct(array $config, string $env)
    {
        $this->database = $config['db'][$env]['database'];
        $this->connection = new \PDO(
            'mysql:host='.$config['db'][$env]['servername'],
            $config['db'][$env]['username'],
            $config['db'][$env]['password']
        );
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }

    public function getDatabaseName(): string
    {
        return $this->database;
    }
}
