<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;
use Atsmacode\PokerGame\Repository\Hand\HandRepository;
use Atsmacode\PokerGame\Repository\Table\TableRepository;

class Game extends Model
{
    protected string $table = 'games';

    private int $table_id;
    private int $mode;

    public function setTableId(int $tableId): void
    {
        $this->table_id = $tableId;
    }

    public function getTableId(): int
    {
        return $this->table_id;
    }

    public function getTable(): ?Table
    {
        $tableRepo = $this->container->get(TableRepository::class);

        return $tableRepo->getTable($this->table_id);
    }

    public function setMode(int $mode): void
    {
        $this->mode = $mode;
    }

    public function getMode(): int
    {
        return $this->mode;
    }

    public function getHand(): ?Hand
    {
        $handRepo = $this->container->get(HandRepository::class);

        return $handRepo->getGameHand($this->id);
    }

    public function find(?array $data = null): ?Game
    {
        return parent::find($data); /* @phpstan-ignore return.type */
    }

    public function create(?array $data = null): ?Game
    {
        return parent::create($data); /* @phpstan-ignore return.type */
    }
}
