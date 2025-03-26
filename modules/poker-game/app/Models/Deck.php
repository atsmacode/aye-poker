<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;

class Deck extends Model
{
    protected string $table = 'decks';
    private string   $cards;

    public function getDeck(): array
    {
        return json_decode($this->cards, true);
    }
}
