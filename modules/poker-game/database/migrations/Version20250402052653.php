<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402052653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create_hand_types_table';
    }

    public function up(Schema $schema): void
    {
        $schema = new Schema();
        $table  = $schema->createTable('hand_types');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('name', 'string', ['length' => 32])->setNotnull(true);
        $table->addColumn('ranking', 'integer', ['length' => 2])->setNotnull(true);
        $table->setPrimaryKey(['id']);

        foreach($schema->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('hand_types');
    }
}
