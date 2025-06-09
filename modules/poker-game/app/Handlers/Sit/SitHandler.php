<?php

namespace Atsmacode\PokerGame\Handlers\Sit;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Atsmacode\PokerGame\State\Game\GameState;
use Atsmacode\PokerGame\State\Player\PlayerState;

/**
 * Handle a Player taking a seat.
 */
class SitHandler
{
    public function __construct(
        private ?GameState $gameState,
        private Hand $hands,
        private TableSeatRepository $tableSeatRepo,
        private PlayerState $playerState
    ) {
    }

    public function handle(int $tableId, ?int $playerId = null, ?int $gameId = null, ?int $thisSeat = null): mixed
    {
        // TODO: Why would playerId be null?
        if (null !== $playerId) {
            $currentSeat = $this->tableSeatRepo->getCurrentPlayerSeat($playerId);
    
            $playerSeat = $thisSeat ? $this->tableSeatRepo->getFirstAvailableSeat($thisSeat) : $currentSeat;
    
            $playerSeat->update(['player_id' => $playerId]);

            $tableId = $playerSeat->getTableId();

            // TODO: Return this within GameState
            if (2 > count($this->tableSeatRepo->hasMultiplePlayers($tableId))) {
                return [
                    'message' => 'Waiting for more players to join.',
                    'players' => $this->playerState->getWaitingPlayerData(
                        $playerId,
                        $playerSeat->getId(),
                        $playerSeat->getNumber()
                    ),
                ];
            }
        }

        $currentHand = $this->hands->find(['game_id' => $gameId, 'table_id' => $tableId, 'completed_on' => null]);

        $this->gameState->setHandIsActive(! $currentHand ? false : true);

        $hand = $currentHand ?? $this->hands->create(['table_id' => $tableId, 'game_id' => $gameId]);

        $this->gameState->initiate($hand);

        return $this->gameState;
    }
}
