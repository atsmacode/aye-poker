<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;

class PlayerAction extends Model
{
    protected string $table = 'player_actions';
    private ?int $bet_amount;
    private bool $big_blind;
    private bool $small_blind;
    private int $player_id;
    private ?int $action_id;
    private int $hand_street_id;
    private int $table_seat_id;
    private int $hand_id;

    public function setBetAmount(?int $betAmount): void
    {
        $this->bet_amount = $betAmount;
    }

    public function getBetAmount(): ?int
    {
        return $this->bet_amount;
    }

    public function setBigBlind(bool $isBigBlind): void
    {
        $this->big_blind = $isBigBlind;
    }

    public function isBigBlind(): bool
    {
        return $this->big_blind;
    }

    public function setSmallBlind(bool $isSmallBlind): void
    {
        $this->small_blind = $isSmallBlind;
    }

    public function isSmallBlind(): bool
    {
        return $this->small_blind;
    }

    public function setPlayerId(int $playerId): void
    {
        $this->player_id = $playerId;
    }

    public function getPlayerId(): int
    {
        return $this->player_id;
    }

    public function setActionId(?int $actionId): void
    {
        $this->action_id = $actionId;
    }

    public function getActionId(): ?int
    {
        return $this->action_id;
    }

    public function setHandStreetId(int $handStreatId): void
    {
        $this->hand_street_id = $handStreatId;
    }

    public function getHandStreetId(): int
    {
        return $this->hand_street_id;
    }

    public function setTableSeatId(int $tableSeatId): void
    {
        $this->table_seat_id = $tableSeatId;
    }

    public function getTableSeatId(): int
    {
        return $this->table_seat_id;
    }

    public function setHandId(int $handId): void
    {
        $this->hand_id = $handId;
    }

    public function getHandId(): int
    {
        return $this->hand_id;
    }

    public function find(?array $data = null): ?PlayerAction
    {
        return parent::find($data); /* @phpstan-ignore return.type */
    }
}
