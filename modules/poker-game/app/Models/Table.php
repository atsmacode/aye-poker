<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;

class Table extends Model
{
    protected string $table = 'tables';

    public function getSeats(?int $tableId = null): ?array
    {
        $tableId = $tableId ?? $this->id;

        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('table_seats', 'ts')
                ->where('table_id = '.$queryBuilder->createNamedParameter($tableId))
                ->andWhere('player_id IS NOT NULL');

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function hasMultiplePlayers(?int $tableId = null): ?array
    {
        $tableId = $tableId ?? $this->id;

        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('table_seats', 'ts')
                ->where('table_id = '.$queryBuilder->createNamedParameter($tableId))
                ->andWhere('player_id IS NOT NULL');

            return $queryBuilder->executeQuery() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
