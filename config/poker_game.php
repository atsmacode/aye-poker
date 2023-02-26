<?php

return [
    'poker_game' => [
        'db' => [
            'live' => [
                'servername' => 'localhost',
                'username'   => 'root',
                'password'   => 'PASSWORD',
                'database'   => 'poker_game',
                'driver'     => 'pdo_mysql',
            ],
            'test' => [
                'servername' => 'localhost',
                'username'   => 'root',
                'password'   => 'PASSWORD',
                'database'   => 'poker_game_test',
                'driver'     => 'pdo_mysql',
            ],
        ],
        'logger' => [
            'path' => 'C:\laragon\tmp\log'
        ],
    ],
];
