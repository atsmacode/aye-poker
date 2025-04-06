<?php

namespace Atsmacode\PokerGame\Controllers\PotLimitOmaha;

use Atsmacode\PokerGame\Controllers\SitController as BaseSitController;
use Atsmacode\PokerGame\GamePlay\GameStyle\PotLimitOmaha;

class SitController extends BaseSitController
{
    protected string $game = PotLimitOmaha::class;
}
