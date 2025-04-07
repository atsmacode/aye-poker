<?php

namespace Atsmacode\PokerGame\Repository\Stack;

use Atsmacode\Framework\Database\Database;

class StackRepository extends Database
{
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
