<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;

class Table extends Model
{
    protected string $table = 'tables';

    public function getSeats(): ?array
    {
        try {
            $tableSeatRepo = $this->container->get(TableSeatRepository::class);

            return $tableSeatRepo->getSeats($this->id);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function hasMultiplePlayers(): ?array
    {
        try {
            $tableSeatRepo = $this->container->get(TableSeatRepository::class);

            return $tableSeatRepo->hasMultiplePlayers($this->id);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
