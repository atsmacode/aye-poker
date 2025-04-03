<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402053717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create_actions_table';
    }

    public function up(Schema $schema): void
    {
        $schema = new Schema();
        $table  = $schema->createTable('actions');
        $table->addColumn('id', 'integer', ['unsigned' => true])->setAutoincrement(true);
        $table->addColumn('name', 'string', ['length' => 32])->setNotnull(true);
        $table->setPrimaryKey(['id']);

        foreach($schema->toSql($this->platform) as $sql) {
            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('actions');
    }
}
