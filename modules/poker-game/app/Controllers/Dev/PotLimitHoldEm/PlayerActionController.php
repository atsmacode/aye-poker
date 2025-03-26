<?php

namespace Atsmacode\PokerGame\Controllers\Dev\PotLimitHoldEm;

use Atsmacode\PokerGame\Controllers\PlayerActionController as BasePlayerActionController;
use Atsmacode\PokerGame\Game\PotLimitHoldEm;

class PlayerActionController extends BasePlayerActionController
{
    protected string $game = PotLimitHoldEm::class;
}
