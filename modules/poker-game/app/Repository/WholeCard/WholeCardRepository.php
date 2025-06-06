<?php

namespace Atsmacode\PokerGame\Repository\WholeCard;

use Atsmacode\Framework\Database\Database;

class WholeCardRepository extends Database
{
    public function getWholeCards(int $handId, int $playerId): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select(
                    'c.*',
                    'wc.player_id',
                    'r.name rankName',
                    'r.abbreviation rankAbbreviation',
                    's.name suit',
                    's.abbreviation suitAbbreviation',
                    'r.ranking ranking '
                )
                ->from('whole_cards', 'wc')
                ->leftJoin('wc', 'cards', 'c', 'wc.card_id = c.id')
                ->leftJoin('c', 'ranks', 'r', 'c.rank_id = r.id')
                ->leftJoin('c', 'suits', 's', 'c.suit_id = s.id')
                ->where('wc.hand_id = '.$queryBuilder->createNamedParameter($handId))
                ->andWhere('wc.player_id = '.$queryBuilder->createNamedParameter($playerId));

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
