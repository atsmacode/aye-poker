<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;
use Atsmacode\PokerGame\Repository\HandStreet\HandStreetRepository;

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
            $handStreetRepo = $this->container->get(HandStreetRepository::class);

            return $handStreetRepo->getStreetCards($handId, $streetId);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
