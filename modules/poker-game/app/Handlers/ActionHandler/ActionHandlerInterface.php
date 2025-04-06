<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame\Handlers\ActionHandler;

use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\Models\Hand;

interface ActionHandlerInterface
{
    public function handle(
        Hand $hand,
        int $playerId,
        int $tableSeatId,
        int $handStreetId,
        ?float $betAmount,
        int $actionId,
        int $active,
        int $stack,
    ): GameState;
}
