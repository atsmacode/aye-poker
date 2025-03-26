<?php

namespace Atsmacode\PokerGame\PotHandler;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Models\Stack;

class PotHandler
{
    public function __construct(
        private Stack $stackModel,
        private Pot   $potModel
    ) {}

    public function initiatePot(Hand $hand): void
    {
        $this->potModel->create(['amount' => 0, 'hand_id' => $hand->getId()]);
    }

    public function awardPot(int $stackAmount, int $potAmount, int $playerId, int $tableId): void
    {
        $amount = $stackAmount + $potAmount;

        $this->stackModel->change($amount, $playerId, $tableId);
    }

    public function updatePot(int $betAmount, int $handId): void
    {
        $pot = $this->potModel->find(['hand_id' => $handId]);
        $pot->update(['amount' => $pot->getAmount() + $betAmount]);
    }
}
