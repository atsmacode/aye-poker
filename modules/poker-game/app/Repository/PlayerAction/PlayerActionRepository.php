<?php

namespace Atsmacode\PokerGame\Repository\PlayerAction;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Models\PlayerAction;

class PlayerActionRepository extends Database
{
    public function getLatestAction(int $handId): ?PlayerAction
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('player_actions', 'pa')
                ->leftJoin('pa', 'player_action_logs', 'pal', 'pa.id = pal.player_status_id')
                ->where('pa.hand_id = '.$handId)
                ->orderBy('pal.id', 'DESC');

            $rows = $queryBuilder->executeQuery() ? $queryBuilder->fetchAssociative() : [];

            $playerAction = $this->container->get(PlayerAction::class);

            return $playerAction->build([$rows]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function getStreetActions(int $handStreetId): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('player_actions', 'pa')
                ->where('pa.hand_street_id = '.$handStreetId);

            return $queryBuilder->executeQuery() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function getBigBlind(int $handId): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('pa.*')
                ->from('player_actions', 'pa')
                ->leftJoin('pa', 'table_seats', 'ts', 'pa.table_seat_id = ts.id')
                ->where('pa.hand_id = '.$queryBuilder->createNamedParameter($handId))
                ->andWhere('pa.big_blind = 1');

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
