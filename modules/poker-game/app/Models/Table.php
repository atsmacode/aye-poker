<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;

class Table extends Model
{
    protected string $table = 'tables';

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
