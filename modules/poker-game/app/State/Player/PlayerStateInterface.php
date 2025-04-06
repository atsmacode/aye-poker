<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame\State\Player;

use Atsmacode\PokerGame\State\Game\GameState;

interface PlayerStateInterface
{
    public function get(GameState $gameState): array;
}
