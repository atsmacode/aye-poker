<?php

namespace Atsmacode\PokerGame\Repository\Game;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Repository\HandStreet\HandStreetRepository;
use Atsmacode\PokerGame\Repository\Player\PlayerRepository;
use Atsmacode\PokerGame\Repository\PlayerAction\PlayerActionRepository;
use Atsmacode\PokerGame\Repository\Table\TableRepository;

/**
 * Responsible for providing the baseline data a Game needs throught the process.
 */
class GameRepository
{
    public function __construct(
        private Hand $hands,
        private TableRepository $tableRepo,
        private PlayerRepository $playerRepo,
        private TableSeat $tableSeats,
        private PlayerActionRepository $playerActionRepo,
        private HandStreetRepository $handStreetRepo
    ) {
    }

    public function getSeats(int $tableId): array
    {
        return $this->tableRepo->getSeats($tableId);
    }

    public function getPlayers(): array
    {
        return $this->hands->getPlayers();
    }

    public function getWholeCards(array $players, int $handId): array
    {
        $wholeCards = [];

        foreach ($players as $player) {
            foreach ($this->playerRepo->getWholeCards($handId, $player['player_id']) as $wholeCard) {
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
        return $this->tableSeats->getBigBlind($handId);
    }

    public function getLatestAction(int $handId): PlayerAction
    {
        return $this->playerActionRepo->getLatestAction($handId);
    }

    public function getStreetActions(int $handStreetId): array
    {
        return $this->handStreetRepo->getStreetActions($handStreetId);
    }
}
