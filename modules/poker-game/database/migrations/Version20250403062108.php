<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403062108 extends AbstractMigration
{
    private int $seats   = 6;
    private int $tableId = 1;

    public function getDescription(): string
    {
        return 'Insert a dev/test table & seats';
    }

    public function up(Schema $schema): void
    {
        $name  = 'Table 1';

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->insert('tables')
            ->setValue('name', $queryBuilder->createNamedParameter($name))
            ->setValue('seats', $queryBuilder->createNamedParameter($this->seats))
            ->setParameter($queryBuilder->createNamedParameter($name), $name)
            ->setParameter($queryBuilder->createNamedParameter($this->seats), $this->seats);

        $queryBuilder->executeStatement();

        $seatsInserted = 0;

        while($seatsInserted < $this->seats){
            $queryBuilder = $this->connection->createQueryBuilder();
            $seatNumber   = $seatsInserted + 1;

            $queryBuilder
                ->insert('table_seats')
                ->setValue('table_id', $queryBuilder->createNamedParameter($this->tableId))
                ->setValue('number', $queryBuilder->createNamedParameter($seatNumber))
                ->setParameter($queryBuilder->createNamedParameter($this->tableId), $this->tableId)
                ->setParameter($queryBuilder->createNamedParameter($seatNumber), $seatNumber);

            $queryBuilder->executeStatement();

            $seatsInserted++;
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('delete * from tables');
        $this->addSql('delete * from table_seats');
    }
}
