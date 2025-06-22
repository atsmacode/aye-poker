<?php

namespace Atsmacode\PokerGame\Repository\Players;

use Atsmacode\Framework\Database\Database;

class PlayerRepository extends Database
{
    public function getTestPlayers(): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('players', 'p')
                ->where('p.id IN (1,2,3,4,5,6)');

            return $queryBuilder->executeQuery() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
