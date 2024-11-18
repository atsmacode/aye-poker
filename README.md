# About

This is the front-end that communicates with my [atsmacode/poker-game](https://github.com/atsmacode/poker-game) package and allows a poker game to be played in the browser with the traditional table and player graphics found in most online poker games. It looks like this:

![Player Waiting at Table](/screenshots/player_waiting.png)
![Flop Action](/screenshots/flop_action.png)

Other simple packages I created are:

* [atsmacode/card-games](https://github.com/atsmacode/card-games) - This could be used to create any card based game
* [atsmacode/framework](https://github.com/atsmacode/framework) - Some basic utilities like Models and DB connections

This is a personal passion-project not intended for use by other people. 

# Documentation

Here's an example of how I plan and map out changes using Lucid Charts:

[Registration & Auth Flow](/documentation/registration_and_auth_flow.pdf)

# Environment

- PHP 8.1.3
- MySQL 8.0.13
- Vue.Js ^3.2.39
- Node.Js 18.12.1

# Quick Start (Linux)

The key steps to get the app started on a local devenvironment are:

* Copy the .env.template to .env
* Create a DB for the Symfony app
* Run the following commands:
  * composer install
  * php bin/console app:start
  * php bin/console doctrine:migrations:migrate
  * npm install && npm run dev
  * symfony server:start

Symfony will start a server and provide a link to the app where you can register & login. Something like: http://127.0.0.1:8000

More details on commands and configs are outlined further down the page.

# Commands

## Build Poker Game DB:

### Linux

Build the test DB:

> dev/builddb

Individual Drop, Create and Seed commands. Remove '-d true' for prodution:

> php dev/BuildAyePoker.php app:create-database -d true

> php dev/BuildAyePoker.php app:build-card-games -d true

> php dev/BuildAyePoker.php app:build-poker-game -d true

### Windows

Build the test DB:

> .\dev\builddb.bat

Individual Drop, Create and Seed commands. Remove '-d true' for prodution:

> php .\dev\BuildAyePoker.php app:create-database -d true

> php .\dev\BuildAyePoker.php app:build-card-games -d true

> php .\dev\BuildAyePoker.php app:build-poker-game -d true

## Aye Poker Migrations

> php bin/console doctrine:migrations:migrate

## Front End

> npm install OR yarn install

> yarn encore dev OR npm run dev

## Laragon

Using Laragon, the following example path to run PHP might be useful:

> C:\laragon\bin\php\php-8.1.3-nts-Win32-vs16-x64/php

# Configs

You need to add poker_game.php to configure your local DB credentials and log path, like so:

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
        'logger' => [
            'path' => '/your/log/file'
        ]
    ],
];

```

# Composer

## Doctrine Dbal

I specified v3.5.5 in composer.json as a -dev version was being pulled in with -W and caused composer update errors
