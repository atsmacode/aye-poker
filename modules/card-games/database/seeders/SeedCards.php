<?php

namespace Atsmacode\CardGames\Database\Seeders;

use Atsmacode\CardGames\Constants\Rank;
use Atsmacode\CardGames\Constants\Suit;
use Atsmacode\Framework\Database\Database;

class SeedCards extends Database
{
    public static array $methods = [
        'seedRanks',
        'seedSuits',
        'seedCards'
    ];
    public function seedRanks()
    {
        try {
            foreach(Rank::ALL as $rank) {
                $stmt         = $this->connection->prepare("INSERT INTO ranks (name, abbreviation, ranking) VALUES (:name, :rankAbbreviation, :ranking)");
                $name         = $rank['rank'];
                $abbreviation = $rank['rankAbbreviation'];
                $ranking      = $rank['ranking'];

                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':rankAbbreviation', $abbreviation);
                $stmt->bindParam(':ranking', $ranking);
                $stmt->executeQuery();
            }
        } catch(\PDOException $e) {
            error_log($e->getMessage());

        }
    }

    public function seedSuits()
    {
        try {
            foreach(Suit::ALL as $suit) {
                $stmt         = $this->connection->prepare("INSERT INTO suits (name, abbreviation) VALUES (:name, :suitAbbreviation)");
                $name         = $suit['suit'];
                $abbreviation = $suit['suitAbbreviation'];

                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':suitAbbreviation', $abbreviation);
                $stmt->execute();
            }
        } catch(\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function seedCards()
    {
        try {
            foreach(Suit::ALL as $suit){
                foreach(Rank::ALL as $rank){
                    $stmt    = $this->connection->prepare("INSERT INTO cards (rank_id, suit_id) VALUES (:rank_id, :suit_id)");
                    $rank_id = $rank['rank_id'];
                    $suit_id = $suit['suit_id'];
                    
                    $stmt->bindParam(':rank_id', $rank_id);
                    $stmt->bindParam(':suit_id', $suit_id);
                    $stmt->execute();
                }
            }
        } catch(\PDOException $e) {
            error_log($e->getMessage());
        }
    }
}
