<?php

namespace Atsmacode\PokerGame\Database\Migrations;

use Atsmacode\Framework\Database\Database;
use Doctrine\DBAL\Schema\Schema;

class CreatePots extends Database
{
    public static array $methods = [
        'createPotsTable',
    ];

    public function createPotsTable(): void
    {
        try {
            $schema = new Schema();
            $table  = $schema->createTable('pots');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('amount', 'integer')->setNotnull(false);
            $table->addColumn('hand_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addForeignKeyConstraint('hands', ['hand_id'], ['id']);
            $table->setPrimaryKey(['id']);

            $dbPlatform = $this->connection->getDatabasePlatform();
            $sql        = $schema->toSql($dbPlatform);

            $this->connection->exec(array_shift($sql));
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
