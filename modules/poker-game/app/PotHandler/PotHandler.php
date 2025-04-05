<?php

namespace Atsmacode\PokerGame\PotHandler;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Models\Stack;

class PotHandler
{
    public function __construct(
        private Stack $stacks,
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

        $this->stacks->change($amount, $playerId, $tableId);
    }

    public function updatePot(float $betAmount, int $handId): void
    {
        $pot = $this->pots->find(['hand_id' => $handId]);
        $pot->update(['amount' => $pot->getAmount() + $betAmount]);
    }
}
