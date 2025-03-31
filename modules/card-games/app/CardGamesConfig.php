<?php

namespace Atsmacode\CardGames;

class CardGamesConfig
{
    public const CONFIG_REF = 'config/card_games.php';

    public function __invoke(): array
    {
        $config = require CardGamesConfig::CONFIG_REF;

        return $config['card_games'];
    }
}
