<?php

namespace Atsmacode\PokerGame\Services\Blinds;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Handlers\Bet\BetHandler;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Services\Pots\PotService;
use Atsmacode\PokerGame\State\Game\GameState;

/**
 * Various methods for posting & incrementing blind bets.
 */
class BlindService extends Database
{
    public function __construct(
        private BetHandler $betHandler,
        private PotService $potService,
        private PlayerActionLog $playerActionLogs,
        private TableSeat $tableSeats,
    ) {
    }

    public function postBlinds(
        GameState $gameState,
        PlayerAction $smallBlind,
        PlayerAction $bigBlind,
    ): void {
        $hand = $gameState->getHand();
        $handId = $hand->getId();
        $tableId = $gameState->tableId();

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
            'hand_id' => $handId,
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
            $handId,
            $sbStack ? $sbStack->getAmount() : 0,
            $smallBlind->getPlayerId(),
            $tableId,
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
            'hand_id' => $handId,
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
            $handId,
            $bbStack ? $bbStack->getAmount() : 0,
            $bigBlind->getPlayerId(),
            $tableId,
            $bigBlind->getBetAmount()
        );
    }
}
