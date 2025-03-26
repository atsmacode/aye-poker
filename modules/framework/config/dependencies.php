<?php

return [
    'invokables' => [
        Atsmacode\Framework\FrameworkConfigProvider::class,
    ],
    'factories' => [
        Atsmacode\Framework\Database\ConnectionInterface::class
            => Atsmacode\Framework\Database\DbalLiveFactory::class,
        PDO::class
            => Atsmacode\Framework\Pdo\PdoLiveFactory::class,
        \Psr\Log\LoggerInterface::class 
            => \Atsmacode\Framework\LoggerFactory::class,
        Atsmacode\Framework\Models\Test::class
            => Atsmacode\Framework\Models\ModelFactory::class,
    ],
];
