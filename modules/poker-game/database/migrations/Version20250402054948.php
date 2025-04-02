<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402054948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $schema = new Schema();
        $table  = $schema->createTable('decks');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('cards', 'string', ['length' => 20000])->setNotnull(true);
        $table->addColumn('hand_id', 'integer', ['unsigned' => true])->setNotnull(true);
        $table->addForeignKeyConstraint('hands', ['hand_id'], ['id']);
        $table->setPrimaryKey(['id']);

        foreach($schema->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('decks');
    }
}
