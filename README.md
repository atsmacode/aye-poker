# About

This is the front-end that communicates with the [atsmacode/poker-game](https://github.com/atsmacode/poker-game) package and allows a poker game to be played in the browser with the traditional table and player graphics found in most online poker games.

## History

- Originally, I developed the game in a somewhat monolithic Laravel repository
- As a personal challenge, I then started fresh with almost entirely vanilla PHP
- I then changed it to use 3rd party packages for container based resources and request lifecycles
- Throughout, I have done a significant amount of refactoring, with the intention of intelligently splitting all the logic into appropriate classes, and splitting the repository into the following individual composer packages:
  - [atsmacode/poker-game](https://github.com/atsmacode/poker-game)
  - [atsmacode/card-games](https://github.com/atsmacode/card-games)
  - [atsmacode/framework](https://github.com/atsmacode/framework)
- It now resides in a Symfony application

This is a personal passion-project not intended for use by other people. 

# Environment

## PHP

8.1.3

## MySQL

8.0.13

## Vue.Js

^3.2.39

## Node.Js

18.12.1

# Commands

## Linux

Build the test DB:

> dev/builddb

Individual Drop, Create and Seed commands. Remove '-d true' for prodution:

> php dev/BuildAyePoker.php app:create-database -d true

> php dev/BuildAyePoker.php app:build-card-games -d true

> php dev/BuildAyePoker.php app:build-poker-game -d true

## Windows

Build the test DB:

> .\dev\builddb.bat

build the front-end:

> yarn encore dev

Individual Drop, Create and Seed commands. Remove '-d true' for prodution:

> php .\dev\BuildAyePoker.php app:create-database -d true

> php .\dev\BuildAyePoker.php app:build-card-games -d true

> php .\dev\BuildAyePoker.php app:build-poker-game -d true

## Laragon

Using Laragon, the following example path to run PHP might be useful:

> C:\laragon\bin\php\php-8.1.3-nts-Win32-vs16-x64/php

# Configs

You need to add poker_game.php to configure your local DB credentials, like so:

```
<?php

return [
    'poker_game' => [
        'db' => [
            'live' => [
                'servername' => 'localhost',
                'username'   => 'DB_USER',
                'password'   => 'DB_PASSWORD',
                'database'   => 'poker_game',
                'driver'     => 'pdo_mysql',
            ],
            'test' => [
                'servername' => 'localhost',
                'username'   => 'DB_USER',
                'password'   => 'DB_PASSWORD',
                'database'   => 'poker_game_test',
                'driver'     => 'pdo_mysql',
            ],
        ],
    ],
];

```

# Composer

## Doctrine Dbal

I specified v3.5.5 in composer.json as a -dev version was being pulled in with -W and caused composer update errors

