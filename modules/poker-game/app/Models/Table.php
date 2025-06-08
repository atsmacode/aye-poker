<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;
use Atsmacode\PokerGame\Repository\Game\GameRepository;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;

class Table extends Model
{
    protected string $table = 'tables';
    private ?int $game_id;

    public function setGameId(?int $gameId): void
    {
        $this->game_id = $gameId;
    }

    public function getGameId(): ?int
    {
        return $this->game_id;
    }

    public function getGame(): ?Game
    {
        $gameRepo = $this->container->get(GameRepository::class);

        return $gameRepo->getTableGame($this->id);
    }

    public function getSeats(): ?array
    {
        $tableSeatRepo = $this->container->get(TableSeatRepository::class);

        return $tableSeatRepo->getSeats($this->id);
    }

    public function hasMultiplePlayers(): ?array
    {
        $tableSeatRepo = $this->container->get(TableSeatRepository::class);

        return $tableSeatRepo->hasMultiplePlayers($this->id);
    }
}
