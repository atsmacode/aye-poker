<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402054411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $schema = new Schema();
        $table  = $schema->createTable('whole_cards');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('card_id', 'integer', ['unsigned' => true])->setNotnull(false);
        $table->addColumn('hand_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('player_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addForeignKeyConstraint('cards', ['card_id'], ['id']);
        $table->addForeignKeyConstraint('hands', ['hand_id'], ['id']);
        $table->addForeignKeyConstraint('players', ['player_id'], ['id']);
        $table->setPrimaryKey(['id']);

        foreach($schema->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('whole_cards');
    }
}
