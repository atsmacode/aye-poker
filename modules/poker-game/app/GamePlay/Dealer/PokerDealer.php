<?php

namespace Atsmacode\PokerGame\GamePlay\Dealer;

use Atsmacode\CardGames\Dealer\Dealer;
use Atsmacode\CardGames\Deck\Deck as BaseDeck;
use Atsmacode\PokerGame\Models\Deck;
use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\HandStreetCard;
use Atsmacode\PokerGame\Models\WholeCard;

class PokerDealer extends Dealer
{
    public function __construct(
        private WholeCard $wholeCards,
        private HandStreetCard $handStreetCards,
        private Deck $deckModel,
    ) {
        $this->deck = (new BaseDeck())->cards;
    }

    public function dealTo(array $players, int $cardCount, ?int $handId): PokerDealer
    {
        $dealtCards = 0;

        while ($dealtCards < $cardCount) {
            foreach ($players as $player) {
                $this->wholeCards->create([
                    'player_id' => $player['player_id'],
                    'card_id' => $this->pickCard()->getCard()['id'],
                    'hand_id' => $handId ?? null,
                ]);
            }

            ++$dealtCards;
        }

        return $this->updateDeck($handId);
    }

    public function dealStreetCards(int $handId, HandStreet $handStreet, int $cardCount): PokerDealer
    {
        $dealtCards = 0;

        while ($dealtCards < $cardCount) {
            $cardId = $this->pickCard()->getCard()['id'];

            $this->handStreetCards->create([
                'card_id' => $cardId,
                'hand_street_id' => $handStreet->getId(),
            ]);

            ++$dealtCards;
        }

        return $this->updateDeck($handId);
    }

    public function dealThisStreetCard(int $handId, string $rank, string $suit, HandStreet $handStreet): PokerDealer
    {
        $cardId = $this->pickCard($rank, $suit)->getCard()['id'];

        $this->handStreetCards->create([
            'card_id' => $cardId,
            'hand_street_id' => $handStreet->getId(),
        ]);

        return $this->updateDeck($handId);
    }

    public function saveDeck(int $handId): PokerDealer
    {
        $this->deckModel->create([
            'hand_id' => $handId,
            'cards' => json_encode($this->deck),
        ]);

        return $this;
    }

    public function updateDeck(int $handId): PokerDealer
    {
        //var_dump($handId);
        $deck = $this->deckModel->find(['hand_id' => $handId]);
        $deck->update(['cards' => json_encode($this->deck)]);

        return $this;
    }

    public function setSavedDeck(int $handId): PokerDealer
    {
        $savedDeck = $this->deckModel->find(['hand_id' => $handId]);

        if ($savedDeck) {
            $this->deck = $savedDeck->getDeck();
        }

        return $this;
    }

    public function shuffle(): PokerDealer
    {
        return parent::shuffle(); /* @phpstan-ignore return.type */
    }
}
