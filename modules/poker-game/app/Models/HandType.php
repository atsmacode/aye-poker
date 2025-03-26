<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;
class HandType extends Model
{
    protected string $table = 'hand_types';
    private string   $name;
    private int      $ranking;

    public function getName(): string
    {
        return $this->name;
    }

    public function getRanking(): int
    {
        return $this->ranking;
    }
}
