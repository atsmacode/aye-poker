<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;

class Stack extends Model
{
    protected string $table = 'stacks';
    private int $amount;

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function change(float $amount, int $playerId, int $tableId): ?int
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->update('stacks')
                ->set('amount', $queryBuilder->createNamedParameter($amount))
                ->where('table_id = '.$queryBuilder->createNamedParameter($tableId))
                ->andWhere('player_id = '.$queryBuilder->createNamedParameter($playerId));

            return $queryBuilder->executeStatement();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
