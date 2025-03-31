<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;

class Pot extends Model
{
    protected string $table = 'pots';
    private int $amount;

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function find(?array $data = null): ?Pot
    {
        return parent::find($data); /* @phpstan-ignore return.type */
    }
}
