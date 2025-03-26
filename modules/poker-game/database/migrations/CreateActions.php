<?php

namespace Atsmacode\PokerGame\Database\Migrations;

use Atsmacode\Framework\Database\Database;
use Doctrine\DBAL\Schema\Schema;

class CreateActions extends Database
{
    public static array $methods = [
        'createActionsTable',
    ];

    public function createActionsTable(): void
    {
        try {
            $schema = new Schema();
            $table  = $schema->createTable('actions');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('name', 'string', ['length' => 32])->setNotnull(true);
            $table->setPrimaryKey(['id']);

            $dbPlatform = $this->connection->getDatabasePlatform();
            $sql        = $schema->toSql($dbPlatform);

            $this->connection->exec(array_shift($sql));
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
