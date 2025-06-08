<?php

namespace Atsmacode\PokerGame\Services\Games;

use Atsmacode\PokerGame\Models\Game;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;
use Symfony\Component\HttpFoundation\Request;

class GameService
{
    public function __construct(
        private Table $tables,
        private TableSeat $tableSeats,
        private Player $players,
        private Game $games,
    ) {
    }

    public function create(mixed $request): ?Game
    {
        // Currently supporting test mode & six seats only.
        $seatCount = 6;
        $playerCount = $request instanceof Request ? $request->get('player_count') : $request['player_count'];
        $mode = $request instanceof Request ? $request->get('mode') : $request['mode'];

        $table = $this->tables->create(['seats' => $seatCount, 'name' => 'Test Table']);

        $seatsInserted = 0;
        $seats = []; // TODO $table->getSeats() was returning [], so doing a manual array here instead.

        while ($seatsInserted < $seatCount) {
            $seats[] = $this->tableSeats->create([
                'table_id' => $table->getId(),
                'number' => $seatsInserted + 1,
            ]);

            ++$seatsInserted;
        }

        foreach ($seats as $tableSeat) {
            if ($tableSeat->getNumber() > $playerCount) {
                break;
            }

            // The first players in the DB are the 'test' players. That matches the seat numbers.
            $player = $this->players->find(['id' => $tableSeat->getNumber()]);

            $tableSeat->update(['player_id' => $player->getId()]);
        }

        return $this->games->create(['table_id' => $table->getId(), 'mode' => $mode]);
    }
}
