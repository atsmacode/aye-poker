<?php

namespace Atsmacode\PokerGame\Repository\Game;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Models\Game;

/**
 * Responsible for providing the baseline data a Game needs throught the process.
 */
class GameRepository extends Database
{
    public function getGame(int $gameId): ?Game
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('games')
                ->where('id = '.$queryBuilder->createNamedParameter($gameId));

            $rows = $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];

            $games = $this->container->build(Game::class);

            return $games->build($rows);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function getTableGame(int $tableId): ?Game
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('games')
                ->where('table_id = '.$queryBuilder->createNamedParameter($tableId))
                ->andWhere('games.completed_on IS NULL')
                ->orderBy('id', 'DESC')
                ->setMaxResults(1);

            $rows = $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];

            $games = $this->container->build(Game::class);

            return $games->build($rows);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
