<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Atsmacode\PokerGame\Constants\Action;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403164220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'seed_actions';
    }

    public function up(Schema $schema): void
    {
        foreach(Action::ALL as $action) {
            $queryBuilder = $this->connection->createQueryBuilder();

            $queryBuilder
                ->insert('actions')
                ->setValue('name', $queryBuilder->createNamedParameter($action['name']))
                ->setParameter($queryBuilder->createNamedParameter($action['name']), $action['name']);

            $this->addSql($queryBuilder->getSql(), $queryBuilder->getParameters());
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('delete * from actions');
    }
}
