# About

I created this repository to provide some basic resources for use in other packages I've been working on. 

It essentially consists of the following:

 - Commands for migrations
 - Container based database connections
 - Model classes

This is intended for my own personal use.

# Environment

## PHP

8.1.3

## MySQL

8.0.13

# Commands

## Linux
Run the unit test suite:

> dev/phpunit

## Windows
Run the unit test suite:

> .\dev\runtests.bat

# Usage

The example configs and migrations are included mainly as examples of how I am currently using this framework.

## Migrator

You can use the base Migrator to migrate any set of classes. The CreateDatabase class included can be used to drop/create a DB. 

In the ExampleMigrator, a Laminas\ServiceManager\ServiceManager is passed into the constructor. It has a DB connection set in the dependency map using app/FrameworkConfigProvider.php.

The base app/Console/Commands/Migrator.php looks for the Atsmacode\Framework\Database\ConnectionInterface and uses the given DB credentials for all migrations.

This interface is also used for the Model DB connections throughout a request lifecycle.

You can then run a command like so (-d true is for the 'test' DB credentials in the config array, remove this for 'live' DB):

> php dev/SymfonyApplication.php app:build-framework -d true

A standalone command to create a DB is provided (you can add it to the SymfonyApplication in the same way as the ExampleMigrator):

> app/Console/Commands/CreateDatabase.php

> php dev/SymfonyApplication.php app:create-database -d true

# Configs

You need to add framework.php to configure your local DB credentials, like so:

```
<?php

return [
    'framework' => [
        'db' => [
            'live' => [
                'servername' => 'localhost',
                'username'   => 'DB_USER',
                'password'   => 'DB_PASSWORD',
                'database'   => 'framework',
                'driver'     => 'pdo_mysql',
            ],
            'test' => [
                'servername' => 'localhost',
                'username'   => 'DB_USER',
                'password'   => 'DB_PASSWORD',
                'database'   => 'framework_test',
                'driver'     => 'pdo_mysql',
            ],
        ]
    ]
];
```
