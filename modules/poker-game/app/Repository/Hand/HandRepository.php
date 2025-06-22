<?php

namespace Atsmacode\PokerGame\Repository\Hand;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Models\Hand;

class HandRepository extends Database
{
    public function getActiveGameHand(int $gameId): ?Hand
    {
        $query = sprintf('SELECT * FROM hands WHERE game_id = %d AND completed_on IS NULL ORDER BY id DESC LIMIT 1', $gameId);

        try {
            /**
             * @todo Using query builder here returns no results and causes:
             * SQLSTATE[HY000]: General error: 2014 Cannot execute queries while
             * other unbuffered queries are active.
             */
            // $queryBuilder = $this->connection->createQueryBuilder();
            // $queryBuilder
            //     ->select('*')
            //     ->from('hands')
            //     ->orderBy('id', 'DESC')
            //     ->setMaxResults(1);

            // $row = $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];

            $stmt = $this->connection->prepare($query);
            $results = $stmt->executeQuery();
            $rows = $results->fetchAllAssociative();

            $hands = $this->container->build(Hand::class);

            return $hands->build([$rows[0]]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
