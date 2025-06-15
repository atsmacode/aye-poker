<?php

namespace Atsmacode\Framework\Console\Commands;

use Atsmacode\Framework\Migrations\CreateDatabase as CreateDatabaseMigration;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:create-database',
    description: 'Populate the DB with all resources (legacy, use Symfony migrations instead)',
    hidden: false,
    aliases: ['app:create-database']
)]
class CreateDatabase extends Migrator
{
    protected array $buildClasses = [
        CreateDatabaseMigration::class,
    ];

    /**
     * @var string
     */
    protected static $defaultName = 'app:create-database';
}
