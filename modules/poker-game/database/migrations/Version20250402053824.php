<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Atsmacode\PokerGame\Enums\GameMode;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402053824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create_games_table';
    }

    public function up(Schema $schema): void
    {
        $schema = new Schema();
        $table  = $schema->createTable('games');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('table_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addColumn('mode', 'smallint', ['default' => GameMode::TEST->value])->setNotnull(true);
        $table->addColumn('completed_on', 'datetime')->setNotnull(false);
        $table->addForeignKeyConstraint('tables', ['table_id'], ['id']);
        $table->setPrimaryKey(['id']);

        foreach($schema->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('streets');
    }
}
