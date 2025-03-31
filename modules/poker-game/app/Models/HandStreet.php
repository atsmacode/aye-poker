<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;

class HandStreet extends Model
{
    protected string $table = 'hand_streets';

    public function cards(): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('hand_street_cards')
                ->where('hand_street_id = '.$queryBuilder->createNamedParameter($this->id));

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

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
}
