<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;

class Stack extends Model
{
    protected string $table = 'stacks';
    private int $amount;

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
