<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Atsmacode\PokerGame\Constants\HandType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403061730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert all Poker hand types';
    }

    public function up(Schema $schema): void
    {
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
    }

    public function down(Schema $schema): void
    {
        $this->addSql('delete * from hand_types');
    }
}
