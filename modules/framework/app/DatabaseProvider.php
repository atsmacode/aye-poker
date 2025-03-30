<?php

namespace Atsmacode\Framework;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class DatabaseProvider
{
    public static function getConnection(array $config, string $env): Connection
    {
        return DriverManager::getConnection([
            'dbname'   => $config['db'][$env]['database'],
            'user'     => $config['db'][$env]['username'],
            'password' => $config['db'][$env]['password'],
            'host'     => $config['db'][$env]['servername'],
            'driver'   => $config['db'][$env]['driver'],
        ]);
    }
}
