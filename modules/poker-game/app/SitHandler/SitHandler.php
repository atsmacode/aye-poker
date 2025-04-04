<?php

namespace Atsmacode\PokerGame\SitHandler;

use Atsmacode\PokerGame\Models\TableSeat;

class SitHandler
{
    public function __construct(private TableSeat $tableSeats)
    {
    }

    public function sit(int $playerId, ?int $thisSeat = null): TableSeat
    {
        $currentSeat = $this->tableSeats->getCurrentPlayerSeat($playerId);

        if (null !== $currentSeat) {
            return $currentSeat;
        }

        $tableSeat = $this->tableSeats->getFirstAvailableSeat($thisSeat);

        $tableSeat->update(['player_id' => $playerId]);

        return $tableSeat;
    }
}
