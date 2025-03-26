# About

A database driven package that provides logic to drive a basic Texas Hold-Em poker game.

It can be pulled in via composer to be used within a parent front-end package, but could also be used within an independent container as an API.

A brief description of how it works is:

- The SitController starts a new Hand
- The PlayerActionController accept a Request containing information on which Hand is being played, and what the last thing to happen was (who was the last to act and what action did they take)
- This data is passed to the ActionHandler which updates the status of all players accordingly and returns a GameState object containing all the information of the current Hand
- The GameState is passed to a GamePlay class which decides what should happen next in the Hand
- Depending on what the next step is, the logic is delegated to respective HandStep interfaces which carry out the appropriate process for each thing:
  - The players are still betting on a street
  - A new street card should be dealt
  - All bets have been called on the river and we have reached showdown
  - A new Hand should be started
- If we have reached showdown, the Showdown class decides the winner by calling the HandIdentifier which identifies and ranks each hand the players have based on the ranking of active cards (a King would be the active card in a pair of Kings hand, for example) and kickers
- The updated GameState is returned

It is by no means a finished poker game. Here are some of the things it does not yet do:

- Split and side pots
- All-ins
- No-limit betting
- Game winners/losers as opposed to Hand winners/losers
- Incrementing blinds and antes

The testing is primarily based on Feature tests where I've set-up a range of scenarios.

# Documentation

*I've saved some examples of how I plan and map out changes using Lucid Charts:*

**Improvments**

- [Game Play Extraction](/documentation/improvements/gameplay_extraction.pdf)
- [Kickers](/documentation/improvements/kickers.pdf)
- [Log Player Actions](/documentation/improvements/log_player_actions.pdf)
- [Sit Players at Tables](/documentation/improvements/sit_players_at_tables.pdf)

**Planning**

- [Bet & Pot Process](/documentation/planning/bet_and_pot_process.pdf)
- [Database Design](/documentation/planning/database_design.pdf)
- [General Hand Flow](/documentation/planning/general_hand_flow.pdf)

# Environment

## PHP

8.1.3

## MySQL

8.0.13

# Commands

## Linux

Build the test DB:

> dev/builddb

Run the unit test suite:

> dev/phpunit

Individual Drop, Create and Seed commands. Remove '-d true' for prodution:

> php dev/PokerGameApp.php app:create-database -d true

> php dev/PokerGameApp.php app:build-card-games -d true

> php dev/PokerGameApp.php app:build-poker-game -d true

## Windows

Drop, Create and Seed all tables:

> .\dev\builddb.bat

Run the unit test suite:

> .\dev\runtests.bat

Individual Drop, Create and Seed commands. Remove '-d true' for prodution:

> php .\dev\PokerGameApp.php app:create-database -d true

> php .\dev\PokerGameApp.php app:build-card-games -d true

> php .\dev\PokerGameApp.php app:build-poker-game -d true

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
