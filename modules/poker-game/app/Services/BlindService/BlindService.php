<?php

namespace Atsmacode\PokerGame\Services\BlindService;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Handlers\BetHandler\BetHandler;
use Atsmacode\PokerGame\State\GameState\GameState;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Services\PotService\PotService;

/**
 * Various methods for posting & incrementing blind bets.
 */
class BlindService extends Database
{
    public function __construct(
        private BetHandler $betHandler,
        private PotService $potService,
        private PlayerActionLog $playerActionLogs,
        private TableSeat $tableSeats
    ) {
    }

    public function postBlinds(
        Hand $hand,
        PlayerAction $smallBlind,
        PlayerAction $bigBlind,
        GameState $gameState,
    ): void {
        $this->potService->initiatePot($hand);

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

        $this->betHandler->handle(
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

        $this->betHandler->handle(
            $hand,
            $bbStack ? $bbStack->getAmount() : 0,
            $bigBlind->getPlayerId(),
            $hand->getTableId(),
            $bigBlind->getBetAmount()
        );
    }
}
