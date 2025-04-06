<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame\State\PlayerState;

use Atsmacode\PokerGame\State\GameState\GameState;

interface PlayerStateInterface
{
    public function get(GameState $gameState): array;
}
