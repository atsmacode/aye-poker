<?php

namespace Atsmacode\Framework\Database;

use Doctrine\DBAL\Connection;
use PDO;

interface ConnectionInterface
{
    public function getConnection(): Connection|PDO;
    public function getDatabaseName(): string;
}