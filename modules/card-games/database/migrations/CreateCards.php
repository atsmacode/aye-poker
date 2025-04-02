<?php

namespace Atsmacode\CardGames\Database\Migrations;

use Atsmacode\Framework\Database\Database;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

class CreateCards extends Database
{
    public static array $methods = [
        'createRanksTable',
        'createSuitsTable',
        'createCardsTable'
    ];

    /**
     * @return list<string>
     */
    public static function up(Connection $connection)
    {
        $schema = new Schema();
        $ranks  = $schema->createTable('ranks');
        $ranks->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $ranks->addColumn('name', 'string', ['length' => 32])->setNotnull(true);
        $ranks->addColumn('ranking', 'integer', ['length' => 2])->setNotnull(true);
        $ranks->addColumn('abbreviation', 'string', ['length' => 30])->setNotnull(true);
        $ranks->setPrimaryKey(['id']);

        $suits  = $schema->createTable('suits');
        $suits->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $suits->addColumn('name', 'string', ['length' => 32])->setNotnull(true);
        $suits->addColumn('abbreviation', 'string', ['length' => 30])->setNotnull(true);
        $suits->setPrimaryKey(['id']);

        $table  = $schema->createTable('cards');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('rank_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('suit_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addForeignKeyConstraint($ranks, ['rank_id'], ['id']);
        $table->addForeignKeyConstraint($suits, ['suit_id'], ['id']);
        $table->setPrimaryKey(['id']);

        return $schema->toSql($connection->getDatabasePlatform());
    }

    public static function down(Schema $schema): void
    {
        $schema->dropTable('cards');
        $schema->dropTable('suits');
        $schema->dropTable('ranks');
    }

    public function createRanksTable()
    {
        $sql = "CREATE TABLE ranks (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                ranking INT(2) NOT NULL,
                abbreviation VARCHAR(30) NOT NULL
            )";

        try {
            $this->connection->executeQuery($sql);
        } catch(\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function createSuitsTable()
    {
        $sql = "CREATE TABLE suits (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            abbreviation VARCHAR(30) NOT NULL
        )";

        try {
            $this->connection->executeQuery($sql);
        } catch(\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function createCardsTable()
    {
        $sql = "CREATE TABLE cards (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            rank_id INT(6) UNSIGNED NOT NULL,
            suit_id INT(6) UNSIGNED NOT NULL,
            FOREIGN KEY (rank_id) REFERENCES ranks(id),
            FOREIGN KEY (suit_id) REFERENCES suits(id)
        )";

        try {
            $this->connection->executeQuery($sql);
        } catch(\PDOException $e) {
            error_log($e->getMessage());
        }
    }
}
