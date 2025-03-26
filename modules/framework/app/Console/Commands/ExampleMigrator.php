<?php

namespace Atsmacode\Framework\Console\Commands;

use \Atsmacode\Framework\Migrations\CreateTestTable;
use \Atsmacode\Framework\Migrations\CreateDatabase;

#[AsCommand(
    name: 'app:build-framework',
    description: 'Populate the DB with all resources',
    hidden: false,
    aliases: ['app:build-framework']
)]

class ExampleMigrator extends Migrator
{
    protected array $buildClasses = [
        CreateDatabase::class,
        CreateTestTable::class
    ];

    protected static $defaultName = 'app:build-framework';
}
