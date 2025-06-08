<?php

namespace Atsmacode\PokerGame\Repository\TableSeat;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Models\TableSeat;

class TableSeatRepository extends Database
{
    public function find(int $tableSeatId): ?TableSeat
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('table_seats')
                ->where('id = '.$queryBuilder->createNamedParameter($tableSeatId));

            $rows = $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];

            $tableSeat = $this->container->build(TableSeat::class);

            return $tableSeat->build($rows);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
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

    public function bigBlindWins(int $tableSeatId): ?int
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->update('table_seats')
                ->set('can_continue', 1)
                ->where('id = '.$queryBuilder->createNamedParameter($tableSeatId));

            return $queryBuilder->executeStatement();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function playerAfterDealer(int $handId, int $dealer): ?TableSeat
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('ts.*')
                ->from('table_seats', 'ts')
                ->leftJoin('ts', 'player_actions', 'pa', 'ts.id = pa.table_seat_id')
                ->where('pa.hand_id = '.$queryBuilder->createNamedParameter($handId))
                ->andWhere('ts.id > '.$queryBuilder->createNamedParameter($dealer))
                ->andWhere('pa.active = 1')
                ->setMaxResults(1);

            $rows = $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];

            $tableSeat = $this->container->build(TableSeat::class);

            return $tableSeat->build($rows);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function getContinuingBetters(string $handId): ?array
    {
        $raiseId = Action::RAISE_ID;
        $betId = Action::BET_ID;
        $callId = Action::CALL_ID;

        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $expressionBuilder = $this->connection->createExpressionBuilder();

            $queryBuilder
                ->select('ts.*')
                ->from('player_actions', 'pa')
                ->leftJoin('pa', 'table_seats', 'ts', 'pa.table_seat_id = ts.id')
                ->where('pa.hand_id = '.$queryBuilder->createNamedParameter($handId))
                ->andWhere('ts.can_continue = 1')
                ->andWhere(
                    $expressionBuilder->in(
                        'pa.action_id',
                        [
                            $queryBuilder->createNamedParameter($raiseId),
                            $queryBuilder->createNamedParameter($betId),
                            $queryBuilder->createNamedParameter($callId),
                        ]
                    )
                );

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function getFirstAvailableSeat(?int $thisTable = null): ?TableSeat
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('table_seats')
                ->where('table_id != 1')
                ->andWhere('player_id IS NULL');

            if (null !== $thisTable) {
                $queryBuilder->andWhere('table_id = '.$thisTable);
            }

            $rows = $queryBuilder->executeQuery() ? $queryBuilder->fetchAssociative() : [];

            $tableSeat = $this->container->build(TableSeat::class);

            return $tableSeat->build([$rows]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function getCurrentPlayerSeat(int $playerId): ?TableSeat
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('table_seats')
                ->where('player_id = '.$playerId);

            $rows = $queryBuilder->executeQuery() ? $queryBuilder->fetchAssociative() : [];

            if (empty($rows)) {
                return null;
            }

            $tableSeat = $this->container->build(TableSeat::class);

            return $tableSeat->build([$rows]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    /**
     * Not used anywhere. Leaving for now.
     */
    public function getContinuingPlayerSeats(string $handId): ?TableSeat
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('ts.*')
                ->from('player_actions', 'pa')
                ->leftJoin('pa', 'table_seats', 'ts', 'pa.table_seat_id = ts.id')
                ->where('pa.hand_id = '.$queryBuilder->createNamedParameter($handId))
                ->andWhere('pa.active = 1')
                ->andWhere('ts.can_continue = 1');

            $rows = $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];

            $tableSeat = $this->container->build(TableSeat::class);

            return $tableSeat->setContent($rows);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
