<?php

namespace Atsmacode\PokerGame\Tests;

trait HasStreets
{
    protected function setFlop()
    {
        $this->setStreet(2);
    }

    protected function setTurn()
    {
        $this->setStreet(3);
    }

    protected function setRiver()
    {
        $this->setStreet(4);
    }

    protected function setStreet(int $streetId)
    {
        $street = $this->gameState->getStyle()->getStreet($streetId);

        $handStreet = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => $street['name']])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $handStreet,
            $street['community_cards']
        );
    }

    protected function setThisFlop(array $flopCards): void
    {
        $street = $this->gameState->getStyle()->getStreet(2);

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

    protected function setThisTurn(array $card): void
    {
        $this->setStreetCard(3, $card);
    }

    protected function setThisRiver(array $card): void
    {
        $this->setStreetCard(4, $card);
    }

    protected function setStreetCard(int $streetId, array $card): void
    {
        $street = $this->gameState->getStyle()->getStreet($streetId);

        $handStreet = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => $street['name']])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $this->handStreetCards->create([
            'hand_street_id' => $handStreet->getId(),
            'card_id' => $card['card_id'],
        ]);
    }
}
