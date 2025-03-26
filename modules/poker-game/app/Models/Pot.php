<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;
class Pot extends Model
{
    protected string $table = 'pots';
    private int      $amount;
    private int      $hand_id;

    public function getAmount(): int
    {
        return $this->amount;
    }
}
