<?php declare(strict_types=1);

namespace Atsmacode\PokerGame\ActionHandler;

use Atsmacode\PokerGame\BetHandler\BetHandler;
use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Atsmacode\PokerGame\Models\TableSeat;

class ActionHandler implements ActionHandlerInterface
{
    public function __construct(
        private ?GameState      $gameState,
        private PlayerAction    $playerActions,
        private PlayerActionLog $playerActionLogs,
        private BetHandler      $betHandler,
        private TableSeat       $tableSeats
    ) {}

    /**
     * @param $int|null $betAmount
     */
    public function handle(
        Hand $hand,
        int  $playerId,
        int  $tableSeatId,
        int  $handStreetId,
             $betAmount,
        int  $actionId,
        int  $active,
        int  $stack
    ): GameState {
        $playerAction = $this->playerActions->find([
            'player_id'      =>  $playerId,
            'table_seat_id'  =>  $tableSeatId,
            'hand_street_id' =>  $handStreetId
        ]);

        $this->betHandler->handle($hand, $stack, $playerId, $hand->getTableId(), $betAmount);

        $playerAction->update([
            'action_id'  => $actionId,
            'bet_amount' => $betAmount,
            'active'     => $active,
            'updated_at' => date('Y-m-d H:i:s', time())
        ]);

        $this->playerActionLogs->create([
            'player_status_id' => $playerAction->getId(),
            'bet_amount'       => $betAmount,
            'big_blind'        => (int) $playerAction->isBigBlind(),
            'small_blind'      => (int) $playerAction->isSmallBlind(),
            'player_id'        => $playerId,
            'action_id'        => $actionId,
            'hand_id'          => $hand->getId(),
            'hand_street_id'   => $handStreetId,
            'table_seat_id'    => $tableSeatId,
            'created_at'       => date('Y-m-d H:i:s', time())
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
        switch($this->gameState->getLatestAction()->getActionId()){
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
                'can_continue' => $canContinue
            ]);
    }

    private function updateAllOtherSeatsBasedOnLatestAction(): void
    {
        switch($this->gameState->getLatestAction()->getActionId()){
            case Action::BET['id']:
            case Action::RAISE['id']:
                $canContinue = 0;
                break;
            default:
                break;
        }

        if(isset($canContinue)){
            $tableSeats = $this->tableSeats->find(['table_id' => $this->gameState->tableId()]);
            $tableSeats->updateBatch([
                'can_continue' => $canContinue
            ], 'id != ' . $this->gameState->getLatestAction()->getTableSeatId());
        }
    }
}