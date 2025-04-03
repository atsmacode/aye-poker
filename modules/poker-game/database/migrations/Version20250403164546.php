<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403164546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'seed_default_table';
    }

    public function up(Schema $schema): void
    {
        $name = 'Table 2';
        $seats   = 6;
        $tableId = 2;

        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->insert('tables')
            ->setValue('name', $queryBuilder->createNamedParameter($name))
            ->setValue('seats', $queryBuilder->createNamedParameter($seats))
            ->setParameter($queryBuilder->createNamedParameter($name), $name)
            ->setParameter($queryBuilder->createNamedParameter($seats), $seats);

        $this->addSql($queryBuilder->getSql(), $queryBuilder->getParameters());

        $inserted = 0;

        while($inserted < $seats){
            $queryBuilder = $this->connection->createQueryBuilder();
            $seatNumber   = $inserted + 1;

            $queryBuilder
                ->insert('table_seats')
                ->setValue('table_id', $queryBuilder->createNamedParameter($tableId))
                ->setValue('number', $queryBuilder->createNamedParameter($seatNumber))
                ->setParameter($queryBuilder->createNamedParameter($tableId), $tableId)
                ->setParameter($queryBuilder->createNamedParameter($seatNumber), $seatNumber);

            $this->addSql($queryBuilder->getSql(), $queryBuilder->getParameters());

            $inserted++;
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('update table_seats set player_id = null where table_id = 2');
        $this->addSql('delete * from tables where id = 2');
    }
}
