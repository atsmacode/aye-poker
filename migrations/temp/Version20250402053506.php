<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402053506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $schema = new Schema();
        $table  = $schema->createTable('tables');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('name', 'string')->setNotnull(true);
        $table->addColumn('seats', 'integer')->setNotnull(true);
        $table->setPrimaryKey(['id']);

        $table  = $schema->createTable('table_seats');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('number', 'integer')->setNotnull(false);
        $table->addColumn('can_continue', 'boolean', ['default' => 0]);
        $table->addColumn('is_dealer', 'boolean', ['default' => 0]);
        $table->addColumn('player_id', 'integer', ['unsigned' => true])->setNotnull(false);
        $table->addColumn('table_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('updated_at', 'datetime')->setNotnull(false);
        $table->addForeignKeyConstraint('players', ['player_id'], ['id']);
        $table->addForeignKeyConstraint('tables', ['table_id'], ['id']);
        $table->setPrimaryKey(['id']);

        foreach($schema->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('table_seats');
        $schema->dropTable('tables');
    }
}
