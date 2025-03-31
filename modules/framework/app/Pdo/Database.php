<?php

namespace Atsmacode\Framework\Pdo;

use Atsmacode\Framework\ConfigProvider;

/**
 * This is a legacy class, can be removed after PKR9.
 */
class Database
{
    public function __construct(ConfigProvider $configProvider)
    {
        $config = $configProvider->get();
        $env = 'live';

        if (isset($GLOBALS['dev'])) {
            $env = 'test';
        }

        $this->database = $config['db'][$env]['database'];
        $this->connection = new \PDO(
            'mysql:host='.$config['db'][$env]['servername'],
            $config['db'][$env]['username'],
            $config['db'][$env]['password']
        );

        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
