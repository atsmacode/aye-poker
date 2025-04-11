<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Repository\Table\TableRepository;

class Game extends Model
{
    protected string $table = 'games';

    private int $table_id;

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
}
