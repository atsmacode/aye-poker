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
        private PlayerState $playerState,
    ) {
    }

    public function handle(int $tableId, ?int $playerId = null, ?int $gameId = null): mixed
    {
        // Real game only
        if (null !== $playerId) {
            $currentSeat = $this->tableSeatRepo->getCurrentPlayerSeat($playerId);
            $playerSeat = $currentSeat ?? $this->tableSeatRepo->getFirstAvailableSeat($tableId);

            $playerSeat->update(['player_id' => $playerId]);

            if (2 > count($this->tableSeatRepo->hasMultiplePlayers($playerSeat->getTableId()))) {
                $players = $this->playerState->getWaitingPlayerData(
                    $playerId,
                    $playerSeat->getId(),
                    $playerSeat->getNumber()
                );

                return $this->gameState
                    ->setWaiting(true)
                    ->setMessage('Waiting for more players to join.')
                    ->setPlayers($players);
            }
        }

        // Real & test
        $currentHand = $this->hands->find(['game_id' => $gameId, 'table_id' => $tableId, 'completed_on' => null]);

        $this->gameState->setHandWasActive(!$currentHand ? false : true);

        $hand = $currentHand ?? $this->hands->create(['table_id' => $tableId, 'game_id' => $gameId]);

        $this->gameState->initiate($hand); // @phpstan-ignore argument.type

        return $this->gameState;
    }
}
