<?php 

namespace Atsmacode\PokerGame\Tests;

trait HasStreets
{
    protected function setFlop()
    {
        $flop = $this->handStreetModel->create([
            'street_id' =>  $this->streetModel->find(['name' => 'Flop'])->getId(),
            'hand_id' => $this->gameState->handId()
        ]);

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $flop,
            $this->gameState->getGame()->streets[2]['community_cards']
        );
    }

    protected function setTurn()
    {
        $turn = $this->handStreetModel->create([
            'street_id' =>  $this->streetModel->find(['name' => 'Turn'])->getId(),
            'hand_id' => $this->gameState->handId()
        ]);

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $turn,
            $this->gameState->getGame()->streets[3]['community_cards']
        );
    }

    protected function setRiver()
    {
        $river = $this->handStreetModel->create([
            'street_id' =>  $this->streetModel->find(['name' => 'River'])->getId(),
            'hand_id' => $this->gameState->handId()
        ]);

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $river,
            $this->gameState->getGame()->streets[4]['community_cards']
        );
    }

    protected function setThisFlop(array $flopCards): void
    {
        $flop = $this->handStreetModel->create([
            'street_id' => $this->streetModel->find(['name' => $this->gameState->getGame()->streets[1]['name']])->getId(),
            'hand_id'   => $this->gameState->handId()
        ]);

        foreach($flopCards as $card){
            $this->handStreetCardModel->create([
                'hand_street_id' => $flop->getId(),
                'card_id'        => $card['card_id']
            ]);
        }
    }

    protected function setThisTurn(array $turnCard): void
    {
        $turn = $this->handStreetModel->create([
            'street_id' => $this->streetModel->find(['name' => $this->gameState->getGame()->streets[2]['name']])->getId(),
            'hand_id'   => $this->gameState->handId()
        ]);

        $this->handStreetCardModel->create([
            'hand_street_id' => $turn->getId(),
            'card_id'        => $turnCard['card_id']
        ]);
    }

    protected function setThisRiver(array $riverCard): void
    {
        $river = $this->handStreetModel->create([
            'street_id' => $this->streetModel->find(['name' => $this->gameState->getGame()->streets[3]['name']])->getId(),
            'hand_id'   => $this->gameState->handId()
        ]);

        $this->handStreetCardModel->create([
            'hand_street_id' => $river->getId(),
            'card_id'        => $riverCard['card_id']
        ]);
    }
}