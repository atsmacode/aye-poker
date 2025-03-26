<?php

return [
    'invokables' => [
        \Atsmacode\CardGames\CardGamesConfigProvider::class,
    ],
    'factories' => [
        Atsmacode\Framework\Database\ConnectionInterface::class
            => Atsmacode\CardGames\DbalLiveFactory::class,
        PDO::class
            => Atsmacode\CardGames\PdoLiveFactory::class,
        \Psr\Log\LoggerInterface::class 
            => \Atsmacode\CardGames\LoggerFactory::class,
        \Atsmacode\CardGames\Models\Test::class
            => Atsmacode\CardGames\Models\ModelFactory::class,
    ]
];
