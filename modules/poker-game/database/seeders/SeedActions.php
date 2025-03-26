<?php

namespace Atsmacode\PokerGame\Database\Seeders;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Constants\Action;

class SeedActions extends Database
{
    public static array $methods = [
        'seed'
    ];

    public function seed(): void
    {
        try {
            foreach(Action::ALL as $action) {
                $queryBuilder = $this->connection->createQueryBuilder();

                $queryBuilder
                    ->insert('actions')
                    ->setValue('name', $queryBuilder->createNamedParameter($action['name']))
                    ->setParameter($queryBuilder->createNamedParameter($action['name']), $action['name']);

                $queryBuilder->executeStatement();
            }
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
