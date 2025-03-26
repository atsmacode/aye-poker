<?php

namespace Atsmacode\PokerGame\Database\Migrations;

use Atsmacode\Framework\Database\Database;
use Doctrine\DBAL\Schema\Schema;

class CreateStacks extends Database
{
    public static array $methods = [
        'createStacksTable',
    ];

    /**
     * TODO amount is not unsigned to allow negative values
     * until 'player loses/zero-chips feature is added.
     */
    public function createStacksTable(): void
    {
        try {
            $schema = new Schema();
            $table  = $schema->createTable('stacks');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('amount', 'bigint')->setNotnull(false);
            $table->addColumn('player_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addColumn('table_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addForeignKeyConstraint('players', ['player_id'], ['id']);
            $table->addForeignKeyConstraint('tables', ['table_id'], ['id']);
            $table->setPrimaryKey(['id']);

            $dbPlatform = $this->connection->getDatabasePlatform();
            $sql        = $schema->toSql($dbPlatform);

            $this->connection->exec(array_shift($sql));
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
