<?php

namespace Atsmacode\PokerGame\Controllers\Dev\PotLimitHoldEm;

use Atsmacode\PokerGame\Controllers\Dev\SitController as BaseSitController;
use Atsmacode\PokerGame\Game\PotLimitHoldEm;

class SitController extends BaseSitController
{
    protected string $game = PotLimitHoldEm::class;
}
