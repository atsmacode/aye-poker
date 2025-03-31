<?php

namespace Atsmacode\Framework\Database;

use Doctrine\DBAL\Connection;

interface ConnectionInterface
{
    public function getConnection(): Connection|\PDO;

    public function getDatabaseName(): string;
}
