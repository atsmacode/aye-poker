<?php

namespace Atsmacode\PokerGame\Database\Migrations;

use Atsmacode\Framework\Database\Database;
use Doctrine\DBAL\Schema\Schema;

class CreateHands extends Database
{
    public static array $methods = [
        'createHandsTable',
        'createHandStreetsTable',
        'createHandStreetCardsTable'
    ];

    public function createHandsTable(): void
    {
        try {
            $schema = new Schema();
            $table  = $schema->createTable('hands');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('game_type_id', 'integer')->setNotnull(false);
            $table->addColumn('table_id', 'integer')->setNotnull(false);
            $table->addColumn('completed_on', 'datetime')->setNotnull(false);
            $table->addForeignKeyConstraint('tables', ['table_id'], ['id']);
            $table->setPrimaryKey(['id']);

            $dbPlatform = $this->connection->getDatabasePlatform();
            $sql        = $schema->toSql($dbPlatform);

            $this->connection->exec(array_shift($sql));
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }

    public function createHandStreetsTable(): void
    {
        try {
            $schema  = new Schema();
            $table = $schema->createTable('hand_streets');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('street_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addColumn('hand_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addForeignKeyConstraint('hands', ['hand_id'], ['id']);
            $table->addForeignKeyConstraint('streets', ['street_id'], ['id']);
            $table->setPrimaryKey(['id']);

            $dbPlatform = $this->connection->getDatabasePlatform();
            $sql        = $schema->toSql($dbPlatform);

            $this->connection->exec(array_shift($sql));
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }

    public function createHandStreetCardsTable(): void
    {
        try {
            $schema  = new Schema();
            $table = $schema->createTable('hand_street_cards');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('hand_street_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addColumn('card_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addForeignKeyConstraint('hand_streets', ['hand_street_id'], ['id']);
            $table->addForeignKeyConstraint('cards', ['card_id'], ['id']);
            $table->setPrimaryKey(['id']);

            $dbPlatform = $this->connection->getDatabasePlatform();
            $sql        = $schema->toSql($dbPlatform);

            $this->connection->exec(array_shift($sql));
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
