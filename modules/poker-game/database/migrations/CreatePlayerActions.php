<?php

namespace Atsmacode\PokerGame\Database\Migrations;

use Atsmacode\Framework\Database\Database;
use Doctrine\DBAL\Schema\Schema;

class CreatePlayerActions extends Database
{
    public static array $methods = [
        'createPlayerActionsTable',
    ];

    public function createPlayerActionsTable(): void
    {
        try {
            $schema  = new Schema();
            $table = $schema->createTable('player_actions');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('bet_amount', 'integer', ['unsigned' => true])->setNotnull(false);
            $table->addColumn('active', 'boolean', ['default' => 0]);
            $table->addColumn('big_blind', 'boolean', ['default' => 0]);
            $table->addColumn('small_blind', 'boolean', ['default' => 0]);
            $table->addColumn('player_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addColumn('action_id', 'integer', ['unsigned' => true])->setNotnull(false);
            $table->addColumn('hand_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addColumn('hand_street_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addColumn('table_seat_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addColumn('updated_at', 'datetime')->setNotnull(false);
            $table->addForeignKeyConstraint('players', ['player_id'], ['id']);
            $table->addForeignKeyConstraint('actions', ['action_id'], ['id']);
            $table->addForeignKeyConstraint('hands', ['hand_id'], ['id']);
            $table->addForeignKeyConstraint('hand_streets', ['hand_street_id'], ['id']);
            $table->addForeignKeyConstraint('table_seats', ['table_seat_id'], ['id']);
            $table->setPrimaryKey(['id']);

            $dbPlatform = $this->connection->getDatabasePlatform();
            $sql        = $schema->toSql($dbPlatform);

            $this->connection->exec(array_shift($sql));
        } catch(\PDOException $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
