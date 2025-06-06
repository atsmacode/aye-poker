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

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $flop,
            $this->gameState->getStyle()->streets[2]['community_cards']
        );
    }

    protected function setTurn()
    {
        $turn = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => 'Turn'])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $turn,
            $this->gameState->getStyle()->streets[3]['community_cards']
        );
    }

    protected function setRiver()
    {
        $river = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => 'River'])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $river,
            $this->gameState->getStyle()->streets[4]['community_cards']
        );
    }

    protected function setThisFlop(array $flopCards): void
    {
        $flop = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => $this->gameState->getStyle()->streets[1]['name']])->getId(),
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
        $turn = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => $this->gameState->getStyle()->streets[2]['name']])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $this->handStreetCards->create([
            'hand_street_id' => $turn->getId(),
            'card_id' => $turnCard['card_id'],
        ]);
    }

    protected function setThisRiver(array $riverCard): void
    {
        $river = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => $this->gameState->getStyle()->streets[3]['name']])->getId(),
            'hand_id' => $this->gameState->handId(),
        ]);

        $this->handStreetCards->create([
            'hand_street_id' => $river->getId(),
            'card_id' => $riverCard['card_id'],
        ]);
    }
}
