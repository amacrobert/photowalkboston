<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230102181959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Event and Image tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE event (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
                banner_image_id INTEGER DEFAULT NULL, 
                title VARCHAR(255) NOT NULL, 
                date DATETIME NOT NULL, 
                description CLOB DEFAULT NULL, 
                meeting_location CLOB DEFAULT NULL, 
                meeting_instructions CLOB DEFAULT NULL, 
                parking CLOB DEFAULT NULL, 
                model_theme CLOB DEFAULT NULL, 
                photographer_challenge CLOB DEFAULT NULL, 
                facebook_link VARCHAR(255) DEFAULT NULL, 
                password VARCHAR(255) DEFAULT NULL
            )
        ');
        $this->addSql('CREATE INDEX IDX_3BAE0AA73F9CEB4E ON event (banner_image_id)');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, updated DATETIME NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE image');
    }
}
