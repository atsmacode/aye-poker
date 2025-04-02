<?php

declare(strict_types=1);

namespace Atsmacode\CardGames;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401162732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $schema = new Schema();
        $ranks  = $schema->createTable('ranks');
        $ranks->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $ranks->addColumn('name', 'string', ['length' => 32])->setNotnull(true);
        $ranks->addColumn('ranking', 'integer', ['length' => 2])->setNotnull(true);
        $ranks->addColumn('abbreviation', 'string', ['length' => 30])->setNotnull(true);
        $ranks->setPrimaryKey(['id']);

        $suits  = $schema->createTable('suits');
        $suits->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $suits->addColumn('name', 'string', ['length' => 32])->setNotnull(true);
        $suits->addColumn('ranking', 'integer', ['length' => 2])->setNotnull(true);
        $ranks->addColumn('abbreviation', 'string', ['length' => 30])->setNotnull(true);
        $suits->setPrimaryKey(['id']);

        $table  = $schema->createTable('cards');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('rank_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('suit_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addForeignKeyConstraint($ranks, ['suit_id'], ['id']);
        $table->addForeignKeyConstraint($suits, ['rank_id'], ['id']);
        $table->setPrimaryKey(['id']);

        foreach($schema->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('cards');
        $schema->dropTable('suits');
        $schema->dropTable('ranks');
    }
}
