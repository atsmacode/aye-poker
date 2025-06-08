<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame\Handlers\Action;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\State\Game\GameState;

interface ActionHandlerInterface
{
    public function handle(
        Hand $hand,
        int $playerActionId,
        ?float $betAmount,
        int $actionId,
        int $stack
    ): GameState;
}
