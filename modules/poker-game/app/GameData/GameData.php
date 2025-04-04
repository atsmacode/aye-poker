<?php

namespace Atsmacode\PokerGame\GameData;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;

/**
 * Responsible for providing the baseline data a Hand needs throught the process.
 */
class GameData
{
    public function __construct(
        private Hand $hand,
        private Table $table,
        private Player $player,
        private TableSeat $tableSeat,
        private PlayerAction $playerAction,
    ) {
    }

    public function getSeats(int $tableId): array
    {
        return $this->table->getSeats($tableId);
    }

    public function getPlayers(int $handId): array
    {
        return $this->hand->getPlayers($handId);
    }

    public function getWholeCards(array $players, int $handId): array
    {
        $wholeCards = [];

        foreach ($players as $player) {
            foreach ($this->player->getWholeCards($handId, $player['player_id']) as $wholeCard) {
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
        return $this->hand->getCommunityCards($handId);
    }

    public function getBigBlind(int $handId): array
    {
        return $this->tableSeat->getBigBlind($handId);
    }

    public function getLatestAction(int $handId): PlayerAction
    {
        return $this->playerAction->getLatestAction($handId);
    }

    public function getStreetActions(int $handStreetId): array
    {
        return $this->playerAction->getStreetActions($handStreetId);
    }
}
