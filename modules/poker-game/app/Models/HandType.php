<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;

class HandType extends Model
{
    protected string $table = 'hand_types';
    private string $name;
    private int $ranking;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setRanking(int $ranking): void
    {
        $this->ranking = $ranking;
    }

    public function getRanking(): int
    {
        return $this->ranking;
    }
}
