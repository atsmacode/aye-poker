<?php

namespace Atsmacode\PokerGame\HandStep;

use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\TableSeat;

/**
 * Responsible for the actions required if a hand is to continue to the next street.
 */
class NewStreet extends HandStep
{
    public function __construct(
        private Street $street,
        private TableSeat $tableSeat,
        private HandStreet $handStreet,
        private PlayerAction $playerAction,
    ) {
    }

    public function handle(GameState $gameState, ?TableSeat $currentDealer = null): GameState
    {
        $this->gameState = $gameState;

        $handStreetCount = 0 < $this->gameState->handStreetCount() ? $this->gameState->handStreetCount() : 1;

        $newStreetId = $this->street->find([
            'name' => $this->gameState->getGame()->getStreets()[$handStreetCount + 1]['name'],
        ])->getId();

        $handStreet = $this->handStreet->create([
            'street_id' => $newStreetId,
            'hand_id' => $this->gameState->handId(),
        ]);

        $this->gameState->getGameDealer()->dealStreetCards(
            $this->gameState->handId(),
            $handStreet, // @phpstan-ignore argument.type (Model not PlayerAction)
            $this->gameState->getGame()->getStreets()[$handStreetCount + 1]['community_cards']
        )->setSavedDeck($this->gameState->getHand()->getId());

        $this->updatePlayerStatusesOnNewStreet($handStreet->getId());
        $this->gameState->updateHandStreets();
        $this->gameState->setPlayers();
        $this->gameState->setCommunityCards();

        return $this->gameState;
    }

    private function updatePlayerStatusesOnNewStreet(int $handStreetId): void
    {
        $this->tableSeat->find(['table_id' => $this->gameState->tableId()])
            ->updateBatch([
                'can_continue' => 0,
            ], 'table_id = '.$this->gameState->tableId());

        $this->playerAction->find(['hand_id' => $this->gameState->handId()])
            ->updateBatch([
                'action_id' => null,
                'hand_street_id' => $handStreetId,
            ], 'hand_id = '.$this->gameState->handId());

        $this->gameState->setNewStreet();
    }
}
