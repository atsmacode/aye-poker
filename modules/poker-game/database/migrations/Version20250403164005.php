<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403164005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'seed_streets';
    }

    public function up(Schema $schema): void
    {
        $streets = require('config/streets.php');

        foreach($streets as $street) {
            $queryBuilder = $this->connection->createQueryBuilder();

            $queryBuilder
                ->insert('streets')
                ->setValue('name', $queryBuilder->createNamedParameter($street['name']))
                ->setParameter($queryBuilder->createNamedParameter($street['name']), $street['name']);

            $this->addSql($queryBuilder->getSql(), $queryBuilder->getParameters());
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('delete * from streets');
    }
}
