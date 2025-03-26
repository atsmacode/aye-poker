<?php

namespace Atsmacode\PokerGame\Database\Migrations;

use Atsmacode\Framework\Database\Database;
use Doctrine\DBAL\Schema\Schema;

class CreateTables extends Database
{
    public static array $methods = [
        'createTablesTable',
        'createTableSeatsTable'
    ];

    public function createTablesTable(): void
    {
        try {
            $schema = new Schema();
            $table  = $schema->createTable('tables');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('name', 'string')->setNotnull(true);
            $table->addColumn('seats', 'integer')->setNotnull(true);
            $table->setPrimaryKey(['id']);

            $dbPlatform = $this->connection->getDatabasePlatform();
            $sql        = $schema->toSql($dbPlatform);

            $this->connection->exec(array_shift($sql));
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }

    public function createTableSeatsTable(): void
    {
        try {
            $schema = new Schema();
            $table  = $schema->createTable('table_seats');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('number', 'integer')->setNotnull(false);
            $table->addColumn('can_continue', 'boolean', ['default' => 0]);
            $table->addColumn('is_dealer', 'boolean', ['default' => 0]);
            $table->addColumn('player_id', 'integer', ['unsigned' => true])->setNotnull(false);
            $table->addColumn('table_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addColumn('updated_at', 'datetime')->setNotnull(false);
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
