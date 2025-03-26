<?php

namespace Atsmacode\PokerGame\Dealer;

use Atsmacode\PokerGame\Models\HandStreetCard;
use Atsmacode\PokerGame\Models\WholeCard;
use Atsmacode\CardGames\Dealer\Dealer;
use Atsmacode\CardGames\Deck\Deck as BaseDeck;
use Atsmacode\PokerGame\Models\Deck;

class PokerDealer extends Dealer
{
    public function __construct(
        private WholeCard      $wholeCardModel,
        private HandStreetCard $handStreetCardModel,
        private Deck           $deckModel
    ) {
        $this->deck = (new BaseDeck())->cards;
    }

    public function dealTo(array $players, int $cardCount, ?int $handId)
    {
        $dealtCards = 0;

        while($dealtCards < $cardCount){
            foreach($players as $player){
                $this->wholeCardModel->create([
                    'player_id' => $player['player_id'],
                    'card_id'   => $this->pickCard()->getCard()['id'],
                    'hand_id'   => $handId ?? null
                ]);
            }

            $dealtCards++;
        }

        return $this->updateDeck($handId);
    }

    public function dealStreetCards(int $handId, $handStreet, $cardCount)
    {
        $dealtCards = 0;

        while($dealtCards < $cardCount){
            $cardId = $this->pickCard()->getCard()['id'];

            $this->handStreetCardModel->create([
                'card_id'        => $cardId,
                'hand_street_id' => $handStreet->getId()
            ]);

            $dealtCards++;
        }

        return $this->updateDeck($handId);
    }

    /**
     * @param HandStreet $handStreet
     * @param string $rank
     * @param string $suit
     * @return $this
     */
    public function dealThisStreetCard(int $handId, $rank, $suit, $handStreet)
    {
        $cardId = $this->pickCard($rank, $suit)->getCard()['id'];

        $this->handStreetCardModel->create([
            'card_id'        => $cardId,
            'hand_street_id' => $handStreet->getId()
        ]);

        return $this->updateDeck($handId);
    }

    public function saveDeck(int $handId): self
    {
        $this->deckModel->create([
            'hand_id' => $handId,
            'cards'   => json_encode($this->deck)
        ]);

        return $this;
    }

    public function updateDeck(int $handId): self
    {
        $deck = $this->deckModel->find(['hand_id' => $handId]);
        $deck->update(['cards' => json_encode($this->deck)]);

        return $this;
    }

    public function setSavedDeck(int $handId): self
    {
        $savedDeck    = $this->deckModel->find(['hand_id' => $handId]);
        $hasSavedDeck = !empty($savedDeck->getContent());

        if ($hasSavedDeck) { $this->deck = $savedDeck->getDeck(); }

        return $this;
    }
}
