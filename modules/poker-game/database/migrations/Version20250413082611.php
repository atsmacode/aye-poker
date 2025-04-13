<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413082611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'insert_default_game';
    }

    public function up(Schema $schema): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->insert('games')
            ->setValue('table_id', 2)
            ->setValue('mode', 2);

        $this->addSql($queryBuilder->getSql());
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE * FROM games');
    }
}
