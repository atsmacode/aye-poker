<?php

namespace Atsmacode\PokerGame\Database\Seeders;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Constants\HandType;

class SeedHandTypes extends Database
{
    public static array $methods = [
        'seed'
    ];

    public function seed(): void
    {
        try {
            foreach(HandType::ALL as $handType) {
                $queryBuilder = $this->connection->createQueryBuilder();

                $queryBuilder
                    ->insert('hand_types')
                    ->setValue('name', $queryBuilder->createNamedParameter($handType['name']))
                    ->setValue('ranking', $queryBuilder->createNamedParameter($handType['ranking']))
                    ->setParameter($queryBuilder->createNamedParameter($handType['name']), $handType['name'])
                    ->setParameter($queryBuilder->createNamedParameter($handType['ranking']), $handType['ranking']);

                $queryBuilder->executeStatement();
            }
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
