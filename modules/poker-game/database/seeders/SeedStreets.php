<?php

namespace Atsmacode\PokerGame\Database\Seeders;

use Atsmacode\Framework\Database\Database;

class SeedStreets extends Database
{
    public static array $methods = [
        'seed'
    ];

    public function seed(): void
    {
        $streets = require('config/streets.php');

        try {
            foreach($streets as $street) {
                $queryBuilder = $this->connection->createQueryBuilder();

                $queryBuilder
                    ->insert('streets')
                    ->setValue('name', $queryBuilder->createNamedParameter($street['name']))
                    ->setParameter($queryBuilder->createNamedParameter($street['name']), $street['name']);

                $queryBuilder->executeStatement();
            }
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
