<?php

namespace Atsmacode\PokerGame;

class PokerGameConfigProvider
{
    public function __construct(?string $rootDir = null)
    {
        $this->rootDir = $rootDir ?? '';
    }

    public function get(): array
    {
        $config           = require($this->rootDir . 'config/poker_game.php');
        $dependencyConfig = (new DependencyConfig())->get();

        return array_merge($config['poker_game'], $dependencyConfig); 
    }
}
