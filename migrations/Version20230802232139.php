<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230802232139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_A45BDDC171F7E88B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__application AS SELECT id, event_id, name, email, instagram, pursuit, referral, website, experience, open_response, status, subscribed, created, updated FROM application');
        $this->addSql('DROP TABLE application');
        $this->addSql('CREATE TABLE application (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, instagram VARCHAR(255) DEFAULT NULL COLLATE BINARY, pursuit VARCHAR(255) DEFAULT NULL COLLATE BINARY, referral VARCHAR(255) DEFAULT NULL COLLATE BINARY, website VARCHAR(255) DEFAULT NULL COLLATE BINARY, experience VARCHAR(255) DEFAULT NULL COLLATE BINARY, open_response CLOB DEFAULT NULL COLLATE BINARY, status VARCHAR(255) NOT NULL COLLATE BINARY, subscribed BOOLEAN DEFAULT \'1\' NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CONSTRAINT FK_A45BDDC171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO application (id, event_id, name, email, instagram, pursuit, referral, website, experience, open_response, status, subscribed, created, updated) SELECT id, event_id, name, email, instagram, pursuit, referral, website, experience, open_response, status, subscribed, created, updated FROM __temp__application');
        $this->addSql('DROP TABLE __temp__application');
        $this->addSql('CREATE INDEX IDX_A45BDDC171F7E88B ON application (event_id)');
        $this->addSql('DROP INDEX IDX_3BAE0AA73F9CEB4E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, banner_image_id, title, date, description, meeting_location, meeting_instructions, parking, model_theme, photographer_challenge, facebook_link, password FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, banner_image_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, date DATETIME NOT NULL, description CLOB DEFAULT NULL COLLATE BINARY, meeting_location CLOB DEFAULT NULL COLLATE BINARY, meeting_instructions CLOB DEFAULT NULL COLLATE BINARY, parking CLOB DEFAULT NULL COLLATE BINARY, model_theme CLOB DEFAULT NULL COLLATE BINARY, photographer_challenge CLOB DEFAULT NULL COLLATE BINARY, facebook_link VARCHAR(255) DEFAULT NULL COLLATE BINARY, password VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_3BAE0AA73F9CEB4E FOREIGN KEY (banner_image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, banner_image_id, title, date, description, meeting_location, meeting_instructions, parking, model_theme, photographer_challenge, facebook_link, password) SELECT id, banner_image_id, title, date, description, meeting_location, meeting_instructions, parking, model_theme, photographer_challenge, facebook_link, password FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA73F9CEB4E ON event (banner_image_id)');
        $this->addSql('ALTER TABLE image ADD COLUMN credit VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_A45BDDC171F7E88B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__application AS SELECT id, event_id, name, email, instagram, pursuit, referral, website, experience, open_response, status, subscribed, created, updated FROM application');
        $this->addSql('DROP TABLE application');
        $this->addSql('CREATE TABLE application (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, instagram VARCHAR(255) DEFAULT NULL, pursuit VARCHAR(255) DEFAULT NULL, referral VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, experience VARCHAR(255) DEFAULT NULL, open_response CLOB DEFAULT NULL, status VARCHAR(255) NOT NULL, subscribed BOOLEAN DEFAULT \'1\' NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL)');
        $this->addSql('INSERT INTO application (id, event_id, name, email, instagram, pursuit, referral, website, experience, open_response, status, subscribed, created, updated) SELECT id, event_id, name, email, instagram, pursuit, referral, website, experience, open_response, status, subscribed, created, updated FROM __temp__application');
        $this->addSql('DROP TABLE __temp__application');
        $this->addSql('CREATE INDEX IDX_A45BDDC171F7E88B ON application (event_id)');
        $this->addSql('DROP INDEX IDX_3BAE0AA73F9CEB4E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, banner_image_id, title, date, description, meeting_location, meeting_instructions, parking, model_theme, photographer_challenge, facebook_link, password FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, banner_image_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, date DATETIME NOT NULL, description CLOB DEFAULT NULL, meeting_location CLOB DEFAULT NULL, meeting_instructions CLOB DEFAULT NULL, parking CLOB DEFAULT NULL, model_theme CLOB DEFAULT NULL, photographer_challenge CLOB DEFAULT NULL, facebook_link VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO event (id, banner_image_id, title, date, description, meeting_location, meeting_instructions, parking, model_theme, photographer_challenge, facebook_link, password) SELECT id, banner_image_id, title, date, description, meeting_location, meeting_instructions, parking, model_theme, photographer_challenge, facebook_link, password FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA73F9CEB4E ON event (banner_image_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, filename, updated FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, updated DATETIME NOT NULL)');
        $this->addSql('INSERT INTO image (id, filename, updated) SELECT id, filename, updated FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
    }
}
