<?php

namespace Atsmacode\PokerGame\Console\Commands;

use Atsmacode\Framework\Console\Commands\Migrator;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreateActions;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreateDecks;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreateHands;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreateHandTypes;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreatePlayerActionLogs;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreatePlayerActions;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreatePlayers;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreatePots;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreateStacks;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreateStreets;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreateTables;
use Atsmacode\PokerGame\Database\Migrations\Legacy\CreateWholeCards;
use Atsmacode\PokerGame\Database\Seeders\Legacy\SeedActions;
use Atsmacode\PokerGame\Database\Seeders\Legacy\SeedDevTable;
use Atsmacode\PokerGame\Database\Seeders\Legacy\SeedHandTypes;
use Atsmacode\PokerGame\Database\Seeders\Legacy\SeedPlayers;
use Atsmacode\PokerGame\Database\Seeders\Legacy\SeedStreets;
use Atsmacode\PokerGame\Database\Seeders\Legacy\SeedTable;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:build-poker-game',
    description: 'Populate the DB with all resources',
    hidden: false,
    aliases: ['app:build-poker-game']
)]
class BuildPokerGame extends Migrator
{
    protected array $buildClasses = [
        CreateHandTypes::class,
        CreatePlayers::class,
        CreateTables::class,
        CreateActions::class,
        CreateStreets::class,
        CreateHands::class,
        CreateWholeCards::class,
        CreatePlayerActions::class,
        CreateStacks::class,
        CreatePots::class,
        CreateDecks::class,
        SeedHandTypes::class,
        SeedDevTable::class,
        SeedPlayers::class,
        SeedStreets::class,
        SeedActions::class,
        CreatePlayerActionLogs::class,
        SeedTable::class,
    ];

    /**
     * @var string
     */
    protected static $defaultName = 'app:build-poker-game';
}
