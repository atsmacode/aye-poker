<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402054532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create_player_actions_table';
    }

    public function up(Schema $schema): void
    {
        $schema  = new Schema();
        $table = $schema->createTable('player_actions');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('bet_amount', 'integer', ['unsigned' => true])->setNotnull(false);
        $table->addColumn('active', 'boolean', ['default' => 0]);
        $table->addColumn('big_blind', 'boolean', ['default' => 0]);
        $table->addColumn('small_blind', 'boolean', ['default' => 0]);
        $table->addColumn('player_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('action_id', 'integer', ['unsigned' => true])->setNotnull(false);
        $table->addColumn('hand_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('hand_street_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('table_seat_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('updated_at', 'datetime')->setNotnull(false);
        $table->addForeignKeyConstraint('players', ['player_id'], ['id']);
        $table->addForeignKeyConstraint('actions', ['action_id'], ['id']);
        $table->addForeignKeyConstraint('hands', ['hand_id'], ['id']);
        $table->addForeignKeyConstraint('hand_streets', ['hand_street_id'], ['id']);
        $table->addForeignKeyConstraint('table_seats', ['table_seat_id'], ['id']);
        $table->setPrimaryKey(['id']);

        foreach($schema->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('player_actions');
    }
}
