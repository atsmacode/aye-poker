<?php

namespace Atsmacode\PokerGame\Controllers\PotLimitHoldEm;

use Atsmacode\PokerGame\Controllers\PlayerActionController as BasePlayerActionController;
use Atsmacode\PokerGame\GamePlay\Game\PotLimitHoldEm;

class PlayerActionController extends BasePlayerActionController
{
    protected string $game = PotLimitHoldEm::class;
}
