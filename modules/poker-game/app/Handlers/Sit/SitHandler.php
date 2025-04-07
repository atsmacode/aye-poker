<?php

namespace Atsmacode\PokerGame\Handlers\Sit;

use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;

/**
 * Handle a Player taking a seat.
 */
class SitHandler
{
    public function __construct(private TableSeatRepository $tableSeatRepo)
    {
    }

    public function handle(int $playerId, ?int $thisSeat = null): TableSeat
    {
        $currentSeat = $this->tableSeatRepo->getCurrentPlayerSeat($playerId);

        if (null !== $currentSeat) {
            return $currentSeat;
        }

        $tableSeat = $this->tableSeatRepo->getFirstAvailableSeat($thisSeat);

        $tableSeat->update(['player_id' => $playerId]);

        return $tableSeat;
    }
}
