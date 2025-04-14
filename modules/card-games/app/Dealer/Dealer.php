<?php

namespace Atsmacode\CardGames\Dealer;

use Atsmacode\CardGames\Deck\Deck;

class Dealer
{
    public array $deck;
    public array $card;

    public function setDeck(?array $deck = null): self
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

    public function pickCard(?string $rank = null, ?string $suit = null): self
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

        return $this;
    }

    private function pickSpecificCard(string $rank, string $suit): self
    {
        foreach ($this->deck as $index => $card) {
            if ($card['rank'] === $rank && $card['suit'] === $suit) {
                $this->card = $card;

                unset($this->deck[$index]);

                $this->deck = array_values($this->deck); // reindex

                return $this;
            }
        }
    
        throw new \RuntimeException("Card {$rank} of {$suit} not found in deck.");
    }
}
