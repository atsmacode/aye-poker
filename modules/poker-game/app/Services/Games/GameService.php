<?php

namespace Atsmacode\PokerGame\Services\Games;

use Atsmacode\PokerGame\Enums\GameMode;
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
        $seatCount = 6; // Supporting 6 only just now
        $mode = $request instanceof Request ? $request->get('mode') : $request['mode'];
        $players = $request instanceof Request ? $request->get('players') : $request['players'] ?? [];
        $playerCount = $request instanceof Request ? $request->get('player_count') : $request['player_count'];

        $table = $this->tables->create([
            'seats' => $seatCount,
            'name' => 1 === $mode ? 'Test Table' : 'Real Table',
        ]);

        $seats = [];
        for ($i = 1; $i <= $seatCount; ++$i) {
            $seats[] = $this->tableSeats->create([
                'table_id' => $table->getId(),
                'number' => $i,
            ]);
        }

        if ($mode === GameMode::TEST->value) {
            // player IDs match seat numbers
            foreach ($seats as $seat) {
                if ($seat->getNumber() > $playerCount) { /* @phpstan-ignore method.notFound */
                    break;
                }

                $player = $this->players->find(['id' => $seat->getNumber()]); /* @phpstan-ignore method.notFound */
                $seat->update(['player_id' => $player->getId()]);
            }
        } else {
            $inserted = 0;

            foreach ($players as $player) {
                $seats[$inserted]->update(['player_id' => $player->getPlayerId()]);
                ++$inserted;
            }
        }

        return $this->games->create([
            'table_id' => $table->getId(),
            'mode' => $mode,
        ]);
    }
}
