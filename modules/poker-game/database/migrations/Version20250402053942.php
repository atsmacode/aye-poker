<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402053942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create_hands_tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $schema = new Schema();
        $table  = $schema->createTable('hands');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('table_id', 'integer', ['unsigned' => true])->setNotnull(false);
        $table->addColumn('game_id', 'integer', ['unsigned' => true])->setNotnull(false);
        $table->addColumn('completed_on', 'datetime')->setNotnull(false);
        $table->addForeignKeyConstraint('tables', ['table_id'], ['id']);
        $table->setPrimaryKey(['id']);

        $table = $schema->createTable('hand_streets');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('street_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('hand_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addForeignKeyConstraint('hands', ['hand_id'], ['id']);
        $table->addForeignKeyConstraint('streets', ['street_id'], ['id']);
        $table->setPrimaryKey(['id']);

        $table = $schema->createTable('hand_street_cards');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('hand_street_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('card_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addForeignKeyConstraint('hand_streets', ['hand_street_id'], ['id']);
        $table->addForeignKeyConstraint('cards', ['card_id'], ['id']);
        $table->setPrimaryKey(['id']);

        foreach($schema->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('hand_street_cards');
        $schema->dropTable('hand_streets');
        $schema->dropTable('hands');
    }
}
