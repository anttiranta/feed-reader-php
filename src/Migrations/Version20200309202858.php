<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309202858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_2906455C12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__feeds_app_item AS SELECT id, category_id, title, description, link, published_date, comments FROM feeds_app_item');
        $this->addSql('DROP TABLE feeds_app_item');
        $this->addSql('CREATE TABLE feeds_app_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, link VARCHAR(255) DEFAULT NULL COLLATE BINARY, published_date DATETIME NOT NULL, comments VARCHAR(255) DEFAULT NULL COLLATE BINARY, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, CONSTRAINT FK_2906455C12469DE2 FOREIGN KEY (category_id) REFERENCES feeds_app_item_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO feeds_app_item (id, category_id, title, description, link, published_date, comments) SELECT id, category_id, title, description, link, published_date, comments FROM __temp__feeds_app_item');
        $this->addSql('DROP TABLE __temp__feeds_app_item');
        $this->addSql('CREATE INDEX IDX_2906455C12469DE2 ON feeds_app_item (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_2906455C12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__feeds_app_item AS SELECT id, category_id, title, description, link, published_date, comments FROM feeds_app_item');
        $this->addSql('DROP TABLE feeds_app_item');
        $this->addSql('CREATE TABLE feeds_app_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, published_date DATETIME NOT NULL, comments VARCHAR(255) DEFAULT NULL, created_date DATETIME NOT NULL, updated_date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO feeds_app_item (id, category_id, title, description, link, published_date, comments) SELECT id, category_id, title, description, link, published_date, comments FROM __temp__feeds_app_item');
        $this->addSql('DROP TABLE __temp__feeds_app_item');
        $this->addSql('CREATE INDEX IDX_2906455C12469DE2 ON feeds_app_item (category_id)');
    }
}
