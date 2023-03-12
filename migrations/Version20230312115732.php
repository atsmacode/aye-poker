<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230312115732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    /** @todo Only connected to the aye_poker DB, do we need foreign player_id set from poker_game? */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE user_player (
                user_id INT NOT NULL,
                player_id INT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES user(id)
            )
            DEFAULT CHARACTER SET utf8mb4
            COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
    );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_player');
    }
}
