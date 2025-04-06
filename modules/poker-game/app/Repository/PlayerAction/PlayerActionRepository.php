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
}