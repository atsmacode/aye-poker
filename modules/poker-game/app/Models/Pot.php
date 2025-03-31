<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;

class Pot extends Model
{
    protected string $table = 'pots';
    private int $amount;

    public function getAmount(): int
    {
        return $this->amount;
    }
}
