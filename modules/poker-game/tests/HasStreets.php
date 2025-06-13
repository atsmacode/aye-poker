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

    protected function setStreet(int $number)
    {
        $street = $this->gameState->getStyle()->getStreet($number);

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

    protected function setThisFlop(array $cards): void
    {
        $street = $this->gameState->getStyle()->getStreet(2);

        $handStreet = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => $street['name']])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        foreach ($cards as $card) {
            $this->handStreetCards->create([
                'hand_street_id' => $handStreet->getId(),
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

    protected function setStreetCard(int $number, array $card): void
    {
        $street = $this->gameState->getStyle()->getStreet($number);

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
