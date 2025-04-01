<?php

namespace Atsmacode\CardGames\Console\Commands;

use Atsmacode\CardGames\Database\Migrations\CreateCards;
use Atsmacode\CardGames\Database\Seeders\SeedCards;
use Atsmacode\Framework\Console\Commands\Migrator;
use Atsmacode\Framework\Migrations\CreateDatabase;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:build-card-games',
    description: 'Populate the DB with all resources',
    hidden: false,
    aliases: ['app:build-card-games']
)]
class BuildCardGames extends Migrator
{
    protected array $buildClasses = [
        CreateDatabase::class,
        CreateCards::class,
        SeedCards::class,
    ];

    /**
     * @var string
     */
    protected static $defaultName = 'app:build-card-games';
}
