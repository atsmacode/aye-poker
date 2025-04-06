<?php

namespace Atsmacode\PokerGame\Controllers\PotLimitHoldEm;

use Atsmacode\PokerGame\Controllers\SitController as BaseSitController;
use Atsmacode\PokerGame\GamePlay\GameStyle\PotLimitHoldEm;

class SitController extends BaseSitController
{
    protected string $game = PotLimitHoldEm::class;
}
