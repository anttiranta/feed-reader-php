<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309202152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE feeds_app_item_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category_domain VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX domain_idx ON feeds_app_item_category (category_domain)');
        $this->addSql('CREATE TABLE feeds_app_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, published_date DATETIME NOT NULL, comments VARCHAR(255) DEFAULT NULL, created_date DATETIME NOT NULL, updated_date DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_2906455C12469DE2 ON feeds_app_item (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE feeds_app_item_category');
        $this->addSql('DROP TABLE feeds_app_item');
    }
}
