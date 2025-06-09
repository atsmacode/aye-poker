<?php

namespace Atsmacode\PokerGame\Contracts;

use Atsmacode\PokerGame\State\Game\GameState;

interface ProcessesGameState
{
    public function process(GameState $gameState): GameState;
}