<?php

namespace Atsmacode\CardGames\Dealer;

use Atsmacode\CardGames\Deck\Deck;

class Dealer
{
    public array $deck;
    public array $card;

    public function setDeck(array $deck = null): self
    {
        if ($deck) {
            $this->deck = $deck;
        } else {
            $this->deck = (new Deck())->cards;
        }

        return $this;
    }

    public function getDeck(): array
    {
        return $this->deck;
    }

    public function shuffle(): self
    {
        shuffle($this->deck);

        return $this;
    }

    public function pickCard(string $rank = null, string $suit = null): self
    {
        if (null === $rank && null === $suit) {
            return $this->pickNextCard();
        }

        return $this->pickSpecificCard($rank, $suit);
    }

    public function getCard(): array
    {
        return $this->card;
    }

    private function pickNextCard(): self
    {
        $card = array_shift($this->deck);

        $this->card = $card;

        $reject = array_filter($this->deck, function($value) use($card) {
            return $value !== $card;
        });

        $this->deck = array_values($reject);

        return $this;
    }

    private function pickSpecificCard(string $rank, string $suit): self
    {
        $filter = array_filter($this->deck, function($value) use($rank, $suit) {
            return $value['rank'] === $rank && $value['suit'] === $suit;
        });

        $this->card = array_values($filter)[0];
        $card       = $this->card;

        $reject = array_filter($this->deck, function($value) use($card) {
            return $value !== $card;
        });

        $this->deck = array_values($reject);

        return $this;
    }
}
