<?php

namespace Atsmacode\PokerGame\Database\Migrations;

use Atsmacode\Framework\Database\Database;
use Doctrine\DBAL\Schema\Schema;

class CreateWholeCards extends Database
{
    public static array $methods = [
        'createWholeCardsTable',
    ];

    public function createWholeCardsTable(): void
    {
        try {
            $schema = new Schema();
            $table  = $schema->createTable('whole_cards');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('card_id', 'integer', ['unsigned' => true])->setNotnull(false);
            $table->addColumn('hand_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addColumn('player_id', 'integer', ['unsigned' => true])->setNotnull(true);
            $table->addForeignKeyConstraint('cards', ['card_id'], ['id']);
            $table->addForeignKeyConstraint('hands', ['hand_id'], ['id']);
            $table->addForeignKeyConstraint('players', ['player_id'], ['id']);
            $table->setPrimaryKey(['id']);

            $dbPlatform = $this->connection->getDatabasePlatform();
            $sql        = $schema->toSql($dbPlatform);

            $this->connection->exec(array_shift($sql));
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
