<?php

namespace Atsmacode\CardGames\Database\Migrations;

use Atsmacode\Framework\Database\Database;

class CreateCards extends Database
{
    public static array $methods = [
        'createRanksTable',
        'createSuitsTable',
        'createCardsTable'
    ];

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
