# About

This is a poker game with the traditional table and player graphics found in most online poker games. It looks like this:

![Flop Action](/screenshots/flop_action.png)

Notes on modules:
* `modules/poker-game` - This is the core logic of the game
* `modules/card-games` - This could be used to create any card based game
* `modules/framework` - Some basic utilities like Models and DB connections

Live page updates during game play are powered by the Mercure Hub: https://mercure.rocks/

# Environment

- PHP ^8.2
- MySQL 8.0.13
- Vue.Js ^3.2.39
- Node.Js 23.10.0

# Quick Start (Docker)

1. Run `dev/docker`
    * Docker will provide a link to the app where you can register & login. Something like: http://localhost:8000
2. Now you're ready to play. Click the 'New Game' button:
    * Test Mode: control all players and see all whole cards.
    * Real Mode: select registered users. Log in to play. Password = `password` for Player 1, 2 etc.

# Quick Start (Linux / Mac)

The key steps to get the app started on a local dev environment are:

1. Copy the .env.template to .env
2. Download the [symfony-cli](https://symfony.com/download)
3. Run the following command: `dev/start`
    * This will do the following:
       * Install Composer dependencies
       * Migrate the Aye Poker & Poker Game DBs
       * Install NPM dependencies
       * Start the Symfony local server
    * Symfony will provide a link to the app where you can register & login. Something like: http://127.0.0.1:8000
4. Open a new terminal and switch to the following directory for your OS: `cd mercure/mac` or `cd mercure/linux`. Then:

```
MERCURE_PUBLISHER_JWT_KEY='!ChangeThisMercureHubJWTSecretKey!' \
MERCURE_SUBSCRIBER_JWT_KEY='!ChangeThisMercureHubJWTSecretKey!' \
./mercure run --config dev.Caddyfile
```

5. Now you're ready to play. Click the 'New Game' button:
    * Test Mode: control all players and see all whole cards.
    * Real Mode: select registered users. Log in to play. Password = `password` for Player 1, 2 etc.

Step #4 will start the Mercure Hub. Installation guide can be found here: https://mercure.rocks/docs/hub/install


> If you can't get Mercure running, simply refresh the page after acting in the game to see the updated state of the game.

More details on commands and configs are outlined further down the page.

# Documentation

Here's an example of how I plan and map out changes using Lucid Charts:

[Registration & Auth Flow](/documentation/registration_and_auth_flow.pdf)

# Other Commands

## Build Poker Game DB:

### Linux / Mac

Build the test DB:

> dev/dbtest

Individual Drop, Create and Seed commands listed in that bash file. Remove '-d true' for prodution.

### Windows

Build the test DB:

> .\dev\builddb.bat

Individual Drop, Create and Seed commands listed in that bash file. Remove '-d true' for prodution.

# Unit Tests & Code Style/Standards

> dev/test

> dev/cs

## Front End

> npm install OR yarn install

> yarn encore dev OR npm run dev

## Laragon

Using Laragon, the following example path to run PHP might be useful:

> C:\laragon\bin\php\php-8.1.3-nts-Win32-vs16-x64/php

# Configs

You may need to add poker_game.php to configure your local DB credentials and log path, like so:

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

Similar format for using card-games or framework modules independently.
