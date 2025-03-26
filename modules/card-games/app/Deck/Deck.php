<?php

namespace Atsmacode\CardGames\Deck;

use Atsmacode\CardGames\Constants\Card;
use Atsmacode\CardGames\Factory\CardFactory;

class Deck
{
    public $cards = [
        Card::ACE_CLUBS,
        Card::DEUCE_CLUBS,
        Card::THREE_CLUBS,
        Card::FOUR_CLUBS,
        Card::FIVE_CLUBS,
        Card::SIX_CLUBS,
        Card::SEVEN_CLUBS,
        Card::EIGHT_CLUBS,
        Card::NINE_CLUBS,
        Card::TEN_CLUBS,
        Card::JACK_CLUBS,
        Card::QUEEN_CLUBS,
        Card::KING_CLUBS,
        Card::ACE_DIAMONDS,
        Card::DEUCE_DIAMONDS,
        Card::THREE_DIAMONDS,
        Card::FOUR_DIAMONDS,
        Card::FIVE_DIAMONDS,
        Card::SIX_DIAMONDS,
        Card::SEVEN_DIAMONDS,
        Card::EIGHT_DIAMONDS,
        Card::NINE_DIAMONDS,
        Card::TEN_DIAMONDS,
        Card::JACK_DIAMONDS,
        Card::QUEEN_DIAMONDS,
        Card::KING_DIAMONDS,
        Card::ACE_HEARTS,
        Card::DEUCE_HEARTS,
        Card::THREE_HEARTS,
        Card::FOUR_HEARTS,
        Card::FIVE_HEARTS,
        Card::SIX_HEARTS,
        Card::SEVEN_HEARTS,
        Card::EIGHT_HEARTS,
        Card::NINE_HEARTS,
        Card::TEN_HEARTS,
        Card::JACK_HEARTS,
        Card::QUEEN_HEARTS,
        Card::KING_HEARTS,
        Card::ACE_SPADES,
        Card::DEUCE_SPADES,
        Card::THREE_SPADES,
        Card::FOUR_SPADES,
        Card::FIVE_SPADES,
        Card::SIX_SPADES,
        Card::SEVEN_SPADES,
        Card::EIGHT_SPADES,
        Card::NINE_SPADES,
        Card::TEN_SPADES,
        Card::JACK_SPADES,
        Card::QUEEN_SPADES,
        Card::KING_SPADES
    ];

    public function __construct()
    {
        $this->cards = $this->compileDeck();
    }

    private function compileDeck()
    {
        foreach ($this->cards as $key => $card) {
            $this->cards[$key] = CardFactory::create($card);
        }

        return $this->cards;
    }
}
