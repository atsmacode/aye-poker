<?php

namespace Atsmacode\PokerGame\Repository\Table;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Models\Table;

class TableRepository extends Database
{
    public function getTable(int $tableId): ?Table
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('tables', 't')
                ->where('t.id = '.$queryBuilder->createNamedParameter($tableId))
                ->setMaxResults(1);

            $rows = $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];

            $table = $this->container->build(Table::class);

            return $table->build($rows);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
