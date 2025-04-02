<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Atsmacode\CardGames\Database\Migrations\CreateCards;
use Atsmacode\PokerGame\Database\Migrations\CreateActions;
use Atsmacode\PokerGame\Database\Migrations\CreateDecks;
use Atsmacode\PokerGame\Database\Migrations\CreateHands;
use Atsmacode\PokerGame\Database\Migrations\CreateHandTypes;
use Atsmacode\PokerGame\Database\Migrations\CreatePlayerActionLogs;
use Atsmacode\PokerGame\Database\Migrations\CreatePlayerActions;
use Atsmacode\PokerGame\Database\Migrations\CreatePlayers;
use Atsmacode\PokerGame\Database\Migrations\CreatePots;
use Atsmacode\PokerGame\Database\Migrations\CreateStacks;
use Atsmacode\PokerGame\Database\Migrations\CreateStreets;
use Atsmacode\PokerGame\Database\Migrations\CreateTables;
use Atsmacode\PokerGame\Database\Migrations\CreateWholeCards;
use Atsmacode\CardGames\Database\Seeders\SeedCards;
use Atsmacode\PokerGame\Database\Seeders\SeedActions;
use Atsmacode\PokerGame\Database\Seeders\SeedDevTable;
use Atsmacode\PokerGame\Database\Seeders\SeedHandTypes;
use Atsmacode\PokerGame\Database\Seeders\SeedPlayers;
use Atsmacode\PokerGame\Database\Seeders\SeedStreets;
use Atsmacode\PokerGame\Database\Seeders\SeedTable;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402173708 extends AbstractMigration
{
    /**
     * In the order we need to migrate and seed.
     */
    private array $migrations = [
        CreateCards::class
    ];

    public function getDescription(): string
    {
        return 'Create & seed all Atsmacode\PokerGame schemas';
    }

    public function up(Schema $schema): void
    {
        foreach($this->migrations as $migration) {
            foreach($migration::up($this->connection) as $sql) {
                $this->addSql($sql);
            }
        }
    }

    public function down(Schema $schema): void
    {
        foreach($this->migrations as $migration) {
            $migration::down($schema);
        }
    }
}
