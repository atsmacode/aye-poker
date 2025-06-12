<?php

namespace Atsmacode\PokerGame\Tests;

trait HasStreets
{
    protected function setFlop()
    {
        $flop = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => 'Flop'])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $street = $this->gameState->getStyle()->getStreet(2);

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $flop,
            $street['community_cards']
        );
    }

    protected function setTurn()
    {
        $turn = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => 'Turn'])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $street = $this->gameState->getStyle()->getStreet(3);

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $turn,
            $street['community_cards']
        );
    }

    protected function setRiver()
    {
        $river = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => 'River'])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $street = $this->gameState->getStyle()->getStreet(4);

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $river,
            $street['community_cards']
        );
    }

    protected function setThisFlop(array $flopCards): void
    {
        $street = $this->gameState->getStyle()->getStreet(1);

        $flop = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => $street['name']])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        foreach ($flopCards as $card) {
            $this->handStreetCards->create([
                'hand_street_id' => $flop->getId(),
                'card_id' => $card['card_id'],
            ]);
        }
    }

    protected function setThisTurn(array $turnCard): void
    {
        $street = $this->gameState->getStyle()->getStreet(3);

        $turn = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => $street['name']])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $this->handStreetCards->create([
            'hand_street_id' => $turn->getId(),
            'card_id' => $turnCard['card_id'],
        ]);
    }

    protected function setThisRiver(array $riverCard): void
    {
        $street = $this->gameState->getStyle()->getStreet(4);

        $river = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => $street['name']])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $this->handStreetCards->create([
            'hand_street_id' => $river->getId(),
            'card_id' => $riverCard['card_id'],
        ]);
    }
}
