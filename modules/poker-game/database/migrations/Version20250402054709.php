<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402054709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $schema = new Schema();
        $table  = $schema->createTable('stacks');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('amount', 'bigint')->setNotnull(false);
        $table->addColumn('player_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('table_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addForeignKeyConstraint('players', ['player_id'], ['id']);
        $table->addForeignKeyConstraint('tables', ['table_id'], ['id']);
        $table->setPrimaryKey(['id']);

        foreach($schema->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('stacks');
    }
}
