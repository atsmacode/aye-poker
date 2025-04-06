<?php

namespace Atsmacode\PokerGame\Handlers\BetHandler;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Services\PotService\PotService;

/**
 * Handle a bet performed by a Player.
 * 
 * TODO: Create a Bet Model for use here?
 */
class BetHandler extends Database
{
    public function __construct(
        private PotService $potService,
        private Stack $stacks
    ) {
    }

    /** @todo Don't need the entire hand model, can pass ID */
    public function handle(
        Hand $hand,
        ?int $stackAmount,
        int $playerId,
        int $tableId,
        ?float $betAmount = null,
    ): ?int {
        if ($betAmount) {
            $stack = $stackAmount - $betAmount;

            $this->stacks->change($stack, $playerId, $tableId);
            $this->potService->updatePot($betAmount, $hand->getId());
        }

        return null;
    }
}
