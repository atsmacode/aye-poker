<?php

namespace Atsmacode\PokerGame\Repository\HandStreet;

use Atsmacode\Framework\Database\Database;

class HandStreetRepository extends Database
{
    public function getStreetCards(int $handId, int $streetId): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('hand_streets', 'hs')
                ->leftJoin('hs', 'hand_street_cards', 'hsc', 'hs.id = hsc.hand_street_id')
                ->where('hs.hand_id = '.$queryBuilder->createNamedParameter($handId))
                ->andWhere('hs.street_id = '.$queryBuilder->createNamedParameter($streetId));

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];
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
}