<?php

declare(strict_types=1);

namespace Atsmacode\CardGames\Database\Migrations;

use Atsmacode\CardGames\Constants\Rank;
use Atsmacode\CardGames\Constants\Suit;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402162627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'seed_ranks_and_suits';
    }

    public function up(Schema $schema): void
    {
        foreach(Rank::ALL as $rank) {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->insert('ranks')
                ->setValue('name', $queryBuilder->createNamedParameter($rank['rank']))
                ->setValue('abbreviation', $queryBuilder->createNamedParameter($rank['rankAbbreviation']))
                ->setValue('ranking', $queryBuilder->createNamedParameter($rank['ranking']))
                ->setParameter($queryBuilder->createNamedParameter($rank['rank']), $rank['rank'])
                ->setParameter($queryBuilder->createNamedParameter($rank['rankAbbreviation']), $rank['rankAbbreviation'])
                ->setParameter($queryBuilder->createNamedParameter($rank['ranking']), $rank['ranking']);

            $this->addSql($queryBuilder->getSql(), $queryBuilder->getParameters());
        }

        foreach(Suit::ALL as $suit) {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->insert('suits')
                ->setValue('name', $queryBuilder->createNamedParameter($suit['suit']))
                ->setValue('abbreviation', $queryBuilder->createNamedParameter($suit['suitAbbreviation']))
                ->setParameter($queryBuilder->createNamedParameter($suit['suit']), $suit['suit'])
                ->setParameter($queryBuilder->createNamedParameter($suit['suitAbbreviation']), $suit['suitAbbreviation']);
                
            $this->addSql($queryBuilder->getSql(), $queryBuilder->getParameters());
        }

        foreach(Suit::ALL as $suit){
            foreach(Rank::ALL as $rank){
                $queryBuilder = $this->connection->createQueryBuilder();
                $queryBuilder
                    ->insert('cards')
                    ->setValue('rank_id', $queryBuilder->createNamedParameter($rank['rank_id']))
                    ->setValue('suit_id', $queryBuilder->createNamedParameter($suit['suit_id']))
                    ->setParameter($queryBuilder->createNamedParameter($rank['rank_id']), $rank['rank_id'])
                    ->setParameter($queryBuilder->createNamedParameter($suit['suit_id']), $suit['suit_id']);
                    
                $this->addSql($queryBuilder->getSql(), $queryBuilder->getParameters());
            }
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('delete * from cards');
        $this->addSql('delete * from suits');
        $this->addSql('delete * from ranks');
    }
}
