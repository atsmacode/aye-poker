<?php

namespace Atsmacode\PokerGame\Handlers\SitHandler;

use Atsmacode\PokerGame\Models\TableSeat;

/**
 * Handle a Player taking a seat.
 */
class SitHandler
{
    public function __construct(private TableSeat $tableSeats)
    {
    }

    public function handle(int $playerId, ?int $thisSeat = null): TableSeat
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
