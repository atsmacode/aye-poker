<?php

namespace Atsmacode\PokerGame\SitHandler;

use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;

class SitHandler
{
    public function __construct(
        private GameState $gameState,
        private Table     $tableModel,
        private TableSeat $tableSeatModel
    ) {}

    public function sit(int $playerId, ?int $thisSeat = null): TableSeat
    {
        $currentSeat = $this->tableSeatModel->getCurrentPlayerSeat($playerId);

        if (null !== $currentSeat) { return $currentSeat; }

        $tableSeat = $this->tableSeatModel->getFirstAvailableSeat($thisSeat);

        $tableSeat->update(['player_id' => $playerId]);

        return $tableSeat;
    }
}