<?php

namespace Atsmacode\PokerGame\Repository\Table;

use Atsmacode\Framework\Database\Database;
use Psr\Log\LoggerInterface;

class TableRepository extends Database
{
    public function __construct(
        protected mixed $connection,
        protected LoggerInterface $logger
    ) {
        parent::__construct($connection, $logger);
    }

    public function getSeats(int $tableId): ?array
    {
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

    public function hasMultiplePlayers(int $tableId): ?array
    {
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