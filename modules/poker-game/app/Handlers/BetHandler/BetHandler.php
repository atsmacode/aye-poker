<?php

namespace Atsmacode\PokerGame\Handlers\BetHandler;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\PotHandler\PotHandler;

class BetHandler extends Database
{
    public function __construct(
        private PotHandler $potHandler,
        private PlayerActionLog $playerActionLogs,
        private Stack $stacks,
        private TableSeat $tableSeats,
    ) {
    }

    /** @todo Don't need the entire hand model, can pass ID */
    public function handle(
        Hand $hand,
        ?int $stackAmount,
        int $playerId,
        int $tableId,
        ?float $betAmount = null,
    ): ?int {
        if ($betAmount) {
            $stack = $stackAmount - $betAmount;

            $this->stacks->change($stack, $playerId, $tableId);
            $this->potHandler->updatePot($betAmount, $hand->getId());
        }

        return null;
    }

    public function postBlinds(
        Hand $hand,
        PlayerAction $smallBlind,
        PlayerAction $bigBlind,
        GameState $gameState,
    ): void {
        $this->potHandler->initiatePot($hand);

        $smallBlind->update([
            'action_id' => Action::BET_ID,
            'bet_amount' => 25.0,
            'active' => 1,
            'small_blind' => 1,
            'updated_at' => date('Y-m-d H:i:s', strtotime('- 10 seconds')),
        ]);

        $this->playerActionLogs->create([
            'player_status_id' => $smallBlind->getId(),
            'bet_amount' => 25.0,
            'small_blind' => 1,
            'player_id' => $smallBlind->getPlayerId(),
            'action_id' => Action::BET_ID,
            'hand_id' => $hand->getId(),
            'hand_street_id' => $smallBlind->getHandStreetId(),
            'table_seat_id' => $smallBlind->getTableSeatId(),
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);

        $this->tableSeats->find(['id' => $smallBlind->getTableSeatId()])
            ->update([
                'can_continue' => 0,
            ]);

        $sbStack = $gameState->getStacks()[$smallBlind->getPlayerId()];

        $this->handle(
            $hand,
            $sbStack ? $sbStack->getAmount() : 0,
            $smallBlind->getPlayerId(),
            $hand->getTableId(),
            $smallBlind->getBetAmount()
        );

        $bigBlind->update([
            'action_id' => Action::BET_ID,
            'bet_amount' => 50.0,
            'active' => 1,
            'big_blind' => 1,
            'updated_at' => date('Y-m-d H:i:s', strtotime('- 5 seconds')),
        ]);

        $this->playerActionLogs->create([
            'player_status_id' => $bigBlind->getId(),
            'bet_amount' => 50.0,
            'big_blind' => 1,
            'player_id' => $bigBlind->getPlayerId(),
            'action_id' => Action::BET_ID,
            'hand_id' => $hand->getId(),
            'hand_street_id' => $bigBlind->getHandStreetId(),
            'table_seat_id' => $bigBlind->getTableSeatId(),
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);

        $this->tableSeats->find(['id' => $bigBlind->getTableSeatId()])
            ->update([
                'can_continue' => 0,
            ]);

        $bbStack = $gameState->getStacks()[$bigBlind->getPlayerId()];

        $this->handle(
            $hand,
            $bbStack ? $bbStack->getAmount() : 0,
            $bigBlind->getPlayerId(),
            $hand->getTableId(),
            $bigBlind->getBetAmount()
        );
    }
}
