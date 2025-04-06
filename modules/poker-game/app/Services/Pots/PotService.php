<?php

namespace Atsmacode\PokerGame\Services\Pots;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Repository\Stack\StackRepository;

/**
 * Assorted methods for Pots.
 */
class PotService
{
    public function __construct(
        private StackRepository $stackRepo,
        private Pot $pots,
    ) {
    }

    public function initiatePot(Hand $hand): void
    {
        $this->pots->create(['amount' => 0, 'hand_id' => $hand->getId()]);
    }

    public function awardPot(?int $stackAmount, int $potAmount, int $playerId, int $tableId): void
    {
        $amount = $stackAmount + $potAmount;

        $this->stackRepo->change($amount, $playerId, $tableId);
    }

    public function updatePot(float $betAmount, int $handId): void
    {
        $pot = $this->pots->find(['hand_id' => $handId]);
        $pot->update(['amount' => $pot->getAmount() + $betAmount]);
    }
}
