<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame\Handlers\Action;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Handlers\Bet\BetHandler;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\State\Game\GameState;

/**
 * Handle an Action performed by a Player.
 *
 * TODO: use/return Action Model
 */
class ActionHandler implements ActionHandlerInterface
{
    public function __construct(
        private ?GameState $gameState,
        private PlayerAction $playerActions,
        private PlayerActionLog $playerActionLogs,
        private BetHandler $betHandler,
        private TableSeat $tableSeats,
    ) {
    }

    public function handle(
        Hand $hand,
        int $playerActionId,
        ?float $betAmount,
        int $actionId,
        ?int $stack,
    ): GameState {
        $playerAction = $this->playerActions->find(['id' => $playerActionId]);

        $this->betHandler->handle(
            $hand,
            $stack,
            $playerAction->getPlayerId(),
            $hand->getTableId(),
            $betAmount
        );

        $playerAction->update([
            'action_id' => $actionId,
            'bet_amount' => $betAmount,
            'active' => 1 === $actionId ? 0 : 1,
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        $this->playerActionLogs->create([
            'player_status_id' => $playerAction->getId(),
            'bet_amount' => $betAmount,
            'big_blind' => (int) $playerAction->isBigBlind(),
            'small_blind' => (int) $playerAction->isSmallBlind(),
            'player_id' => $playerAction->getPlayerId(),
            'action_id' => $actionId,
            'hand_id' => $hand->getId(),
            'hand_street_id' => $playerAction->getHandStreetId(),
            'table_seat_id' => $playerAction->getTableSeatId(),
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);

        $this->gameState->initiate($hand);
        $this->gameState->setLatestAction($playerAction);
        $this->gameState->setBigBlind();

        $this->updateSeatStatusOfLatestAction();
        $this->updateAllOtherSeatsBasedOnLatestAction();

        return $this->gameState;
    }

    private function updateSeatStatusOfLatestAction(): void
    {
        switch ($this->gameState->getLatestAction()->getActionId()) {
            case Action::CHECK['id']:
            case Action::CALL['id']:
            case Action::BET['id']:
            case Action::RAISE['id']:
                $canContinue = 1;
                break;
            default:
                $canContinue = 0;
                break;
        }

        $this->tableSeats->find(['id' => $this->gameState->getLatestAction()->getTableSeatId()])
            ->update([
                'can_continue' => $canContinue,
            ]);
    }

    private function updateAllOtherSeatsBasedOnLatestAction(): void
    {
        switch ($this->gameState->getLatestAction()->getActionId()) {
            case Action::BET['id']:
            case Action::RAISE['id']:
                $canContinue = 0;
                break;
            default:
                break;
        }

        if (isset($canContinue)) {
            $tableSeats = $this->tableSeats->find(['table_id' => $this->gameState->tableId()]);
            $tableSeats->updateBatch([
                'can_continue' => $canContinue,
            ], 'id != '.$this->gameState->getLatestAction()->getTableSeatId());
        }
    }
}
