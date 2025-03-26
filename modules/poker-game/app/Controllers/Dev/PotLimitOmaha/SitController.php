<?php

namespace Atsmacode\PokerGame\Controllers\Dev\PotLimitOmaha;

use Atsmacode\PokerGame\Controllers\Dev\SitController as BaseSitController;
use Atsmacode\PokerGame\Game\PotLimitOmaha;

class SitController extends BaseSitController
{
    protected string $game = PotLimitOmaha::class;
}
