<?php

namespace Atsmacode\PokerGame\Repository\GameState;

use Atsmacode\PokerGame\Models\Game;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Repository\Game\GameRepository;
use Atsmacode\PokerGame\Repository\PlayerAction\PlayerActionRepository;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Atsmacode\PokerGame\Repository\WholeCard\WholeCardRepository;

/**
 * Responsible for providing the baseline data a Game needs throught the process.
 */
class GameStateRepository
{
    public function __construct(
        private Hand $hands,
        private TableSeatRepository $tableSeatRepo,
        private WholeCardRepository $wholeCardRepo,
        private PlayerActionRepository $playerActionRepo,
        private GameRepository $gameRepo
    ) {
    }

    public function getSeats(int $tableId): array
    {
        return $this->tableSeatRepo->getSeats($tableId);
    }

    public function getPlayers(): array
    {
        return $this->hands->getPlayers();
    }

    public function getWholeCards(array $players, int $handId): array
    {
        $wholeCards = [];

        foreach ($players as $player) {
            foreach ($this->wholeCardRepo->getWholeCards($handId, $player['player_id']) as $wholeCard) {
                if (array_key_exists($wholeCard['player_id'], $wholeCards)) {
                    array_push($wholeCards[$wholeCard['player_id']], $wholeCard);
                } else {
                    $wholeCards[$wholeCard['player_id']][] = $wholeCard;
                }
            }
        }

        return $wholeCards;
    }

    public function getBigBlind(int $handId): array
    {
        return $this->playerActionRepo->getBigBlind($handId);
    }

    public function getLatestAction(int $handId): PlayerAction
    {
        return $this->playerActionRepo->getLatestAction($handId);
    }

    public function getStreetActions(int $handStreetId): array
    {
        return $this->playerActionRepo->getStreetActions($handStreetId);
    }

    public function getTableGame(int $tableId): Game
    {
        return $this->gameRepo->getTableGame($tableId);
    }
}
