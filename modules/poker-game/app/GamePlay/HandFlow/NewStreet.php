<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\State\Game\GameState;

/**
 * Responsible for the actions required if a hand is to continue to the next street.
 */
class NewStreet implements ProcessesGameState
{
    public function __construct(
        private Street $streets,
        private TableSeat $tableSeats,
        private HandStreet $handStreets,
        private PlayerAction $playerActions,
    ) {
    }

    public function process(GameState $gameState): GameState
    {
        $handStreetCount = 0 < $gameState->handStreetCount() ? $gameState->handStreetCount() : 1;
        $nextStreet = $gameState->getStyle()->getStreets()[$handStreetCount + 1];
        $handId = $gameState->handId();
        $tableId = $gameState->tableId();

        $newStreetId = $this->streets->find(['name' => $nextStreet['name']])->getId();
        $handStreet = $this->handStreets->create(['street_id' => $newStreetId, 'hand_id' => $handId]);

        $gameState->getGameDealer()
            ->dealStreetCards($handId, $handStreet, $nextStreet['community_cards']) // @phpstan-ignore argument.type (Model not PlayerAction)
            ->setSavedDeck($handId);

        $this->tableSeats->find(['table_id' => $tableId])
            ->updateBatch(['can_continue' => 0], 'table_id = '.$tableId);

        $this->playerActions->find(['hand_id' => $handId])
            ->updateBatch([
                'action_id' => null,
                'hand_street_id' => $handStreet->getId(),
            ], 'hand_id = '.$handId);

        $gameState->setNewStreet(true)
            ->loadHandStreets()
            ->loadPlayers()
            ->loadCommunityCards();

        return $gameState;
    }
}
