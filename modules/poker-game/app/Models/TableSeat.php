<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;

class TableSeat extends Model
{
    protected string $table = 'table_seats';
    private ?int $number;
    private bool $can_continue;
    private ?int $player_id;
    private int $table_id;

    public function setCanContinue(bool $canContinue): void
    {
        $this->can_continue = $canContinue;
    }

    public function canContinue(): bool
    {
        return $this->can_continue;
    }

    public function setPlayerId(?int $playerId): void
    {
        $this->player_id = $playerId;
    }

    public function getPlayerId(): int
    {
        return $this->player_id;
    }

    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setTableId(int $tableId): void
    {
        $this->table_id = $tableId;
    }

    public function getTableId(): int
    {
        return $this->table_id;
    }
}
