<?php

namespace Atsmacode\PokerGame\Database\Seeders;

use Atsmacode\Framework\Database\Database;

class SeedDevTable extends Database
{
    public static array $methods = [
        'seed'
    ];

    private int $seats   = 6;
    private int $tableId = 1;

    public function seed(): void
    {
        $this->createTable();
    }

    private function createTable(): void
    {
        $name  = 'Table 1';

        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $queryBuilder
                ->insert('tables')
                ->setValue('name', $queryBuilder->createNamedParameter($name))
                ->setValue('seats', $queryBuilder->createNamedParameter($this->seats))
                ->setParameter($queryBuilder->createNamedParameter($name), $name)
                ->setParameter($queryBuilder->createNamedParameter($this->seats), $this->seats);

            $queryBuilder->executeStatement();

            $this->createTableSeats();
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }

    private function createTableSeats(): void
    {
        try {
            $inserted = 0;

            while($inserted < $this->seats){
                $queryBuilder = $this->connection->createQueryBuilder();
                $seatNumber   = $inserted + 1;

                $queryBuilder
                    ->insert('table_seats')
                    ->setValue('table_id', $queryBuilder->createNamedParameter($this->tableId))
                    ->setValue('number', $queryBuilder->createNamedParameter($seatNumber))
                    ->setParameter($queryBuilder->createNamedParameter($this->tableId), $this->tableId)
                    ->setParameter($queryBuilder->createNamedParameter($seatNumber), $seatNumber);

                $queryBuilder->executeStatement();

                $inserted++;
            }
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
