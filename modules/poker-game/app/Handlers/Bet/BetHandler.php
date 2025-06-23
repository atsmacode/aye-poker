<?php

namespace Atsmacode\PokerGame\Handlers\Bet;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Repository\Stack\StackRepository;
use Atsmacode\PokerGame\Services\Pots\PotService;

/**
 * Handle a bet performed by a Player.
 *
 * TODO: Create a Bet Model for use here?
 */
class BetHandler extends Database
{
    public function __construct(
        private PotService $potService,
        private StackRepository $stackRepo,
    ) {
    }

    /** @todo A lot of params here, probably only need 2 or 3 */
    public function handle(
        int $handId,
        ?int $stackAmount,
        int $playerId,
        int $tableId,
        ?float $betAmount = null,
    ): ?int {
        if ($betAmount) {
            $stack = $stackAmount - $betAmount;

            $this->stackRepo->change($stack, $playerId, $tableId);
            $this->potService->updatePot($betAmount, $handId);
        }

        return null;
    }
}
