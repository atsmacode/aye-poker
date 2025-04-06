<?php

namespace Atsmacode\PokerGame\Repository\Game;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;

/**
 * Responsible for providing the baseline data a Hand needs throught the process.
 */
class GameRepository
{
    public function __construct(
        private Hand $hands,
        private Table $tables,
        private Player $players,
        private TableSeat $tableSeats,
        private PlayerAction $playerActions,
    ) {
    }

    public function getSeats(int $tableId): array
    {
        return $this->tables->getSeats($tableId);
    }

    public function getPlayers(int $handId): array
    {
        return $this->hands->getPlayers($handId);
    }

    public function getWholeCards(array $players, int $handId): array
    {
        $wholeCards = [];

        foreach ($players as $player) {
            foreach ($this->players->getWholeCards($handId, $player['player_id']) as $wholeCard) {
                if (array_key_exists($wholeCard['player_id'], $wholeCards)) {
                    array_push($wholeCards[$wholeCard['player_id']], $wholeCard);
                } else {
                    $wholeCards[$wholeCard['player_id']][] = $wholeCard;
                }
            }
        }

        return $wholeCards;
    }

    public function getCommunityCards(int $handId): array
    {
        return $this->hands->getCommunityCards($handId);
    }

    public function getBigBlind(int $handId): array
    {
        return $this->tableSeats->getBigBlind($handId);
    }

    public function getLatestAction(int $handId): PlayerAction
    {
        return $this->playerActions->getLatestAction($handId);
    }

    public function getStreetActions(int $handStreetId): array
    {
        return $this->playerActions->getStreetActions($handStreetId);
    }
}
