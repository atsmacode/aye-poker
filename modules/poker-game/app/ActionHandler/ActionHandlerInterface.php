<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame\ActionHandler;

use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\Models\Hand;

interface ActionHandlerInterface
{
    public function handle(
        Hand $hand,
        int $playerId,
        int $tableSeatId,
        int $handStreetId,
        float|null $betAmount,
        int $actionId,
        int $active,
        int $stack,
    ): GameState;
}
