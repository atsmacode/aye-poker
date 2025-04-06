<?php

namespace Atsmacode\PokerGame\Controllers\PotLimitOmaha;

use Atsmacode\PokerGame\Controllers\PlayerActionController as BasePlayerActionController;
use Atsmacode\PokerGame\GamePlay\GameStyle\PotLimitOmaha;

class PlayerActionController extends BasePlayerActionController
{
    protected string $game = PotLimitOmaha::class;
}
