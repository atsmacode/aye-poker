<?php

namespace Atsmacode\Framework\Console\Commands;

use Atsmacode\Framework\Migrations\CreateDatabase;
use Atsmacode\Framework\Migrations\CreateTestTable;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:build-framework',
    description: 'Populate the DB with all resources (legacy, use Symfony migrations instead)',
    hidden: false,
    aliases: ['app:build-framework']
)]
class ExampleMigrator extends Migrator
{
    protected array $buildClasses = [
        CreateDatabase::class,
        CreateTestTable::class,
    ];

    /**
     * @var string
     */
    protected static $defaultName = 'app:build-framework';
}
