<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403161747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'seed_table_seats';
    }

    public function up(Schema $schema): void
    {
        $seats    = 6;
        $inserted = 0;

        while($inserted < $seats){
            $seatId = $inserted + 1;
            $name   = 'Player ' . $seatId;

            $queryBuilder = $this->connection->createQueryBuilder();

            $queryBuilder
                ->insert('players')
                ->setValue('name', $queryBuilder->createNamedParameter($name))
                ->setParameter($queryBuilder->createNamedParameter($name), $name);

            $this->addSql($queryBuilder->getSql(), $queryBuilder->getParameters());

            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->update('table_seats')
                ->set('player_id', $queryBuilder->createNamedParameter($seatId))
                ->where('id = ' . $queryBuilder->createNamedParameter($seatId));

            $this->addSql($queryBuilder->getSql(), $queryBuilder->getParameters());

            $inserted++;
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('update table_seats set player_id = null');
        $this->addSql('delete * from players');
    }
}
