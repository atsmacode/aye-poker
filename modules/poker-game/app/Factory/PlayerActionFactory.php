<?php

namespace Atsmacode\PokerGame\Factory;

use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\PlayerActionLog;

/**
 * A class to easily set player actions & associated logs in test suite.
 */
class PlayerActionFactory
{
    public function __construct(
        private PlayerAction $playerActions,
        private PlayerActionLog $playerActionLogs,
    ) {
    }

    public function create(
        int $playerActionId,
        int $handId,
        ?int $actionId,
        ?float $betAmount,
        int $active,
    ): PlayerAction {
        $playerAction = $this->playerActions->find(['id' => $playerActionId]);

        $playerAction->update([
            'action_id' => $actionId,
            'bet_amount' => $betAmount,
            'active' => $active,
        ]);

        if (null !== $actionId) {
            $this->playerActionLogs->create([
                'player_status_id' => $playerAction->getId(),
                'bet_amount' => $playerAction->getBetAmount(),
                'big_blind' => (int) $playerAction->isBigBlind(),
                'small_blind' => (int) $playerAction->isSmallBlind(),
                'player_id' => $playerAction->getPlayerId(),
                'action_id' => $playerAction->getActionId(),
                'hand_id' => $handId,
                'hand_street_id' => $playerAction->getHandStreetId(),
                'table_seat_id' => $playerAction->getTableSeatId(),
                'created_at' => date('Y-m-d H:i:s', time()),
            ]);
        }

        return $playerAction;
    }
}
