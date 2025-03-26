<?php

namespace Atsmacode\CardGames;

class CardGamesConfig
{
    const CONFIG_REF = 'config/card_games.php';

    public function __invoke()
    {
        $config  = require(CardGamesConfig::CONFIG_REF);

        return $config['card_games'];
    }
}
