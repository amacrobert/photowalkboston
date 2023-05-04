<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503030017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Application';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, instagram VARCHAR(255) DEFAULT NULL, pursuit VARCHAR(255) DEFAULT NULL, referral VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, experience VARCHAR(255) DEFAULT NULL, open_response CLOB DEFAULT NULL, created DATETIME NOT NULL, status VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_A45BDDC171F7E88B ON application (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE application');
    }
}
