<?php

namespace Atsmacode\PokerGame\Database\Migrations;

use Atsmacode\Framework\Database\Database;
use Doctrine\DBAL\Schema\Schema;

class CreateDecks extends Database
{
    public static array $methods = [
        'createDecksTable',
    ];

    public function createDecksTable(): void
    {
        try {
            $schema = new Schema();
            $table  = $schema->createTable('decks');

            $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('cards', 'string', ['length' => 20000])->setNotnull(true);
            $table->addColumn('hand_id', 'integer')->setNotnull(true);
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
