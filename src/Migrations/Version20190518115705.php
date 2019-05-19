<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190518115705 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user_category (user_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(user_id, category_id))');
        $this->addSql('CREATE INDEX IDX_E6C1FDC1A76ED395 ON user_category (user_id)');
        $this->addSql('CREATE INDEX IDX_E6C1FDC112469DE2 ON user_category (category_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, title, description, date, price, location, picture_id FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, date DATETIME NOT NULL, price DOUBLE PRECISION DEFAULT NULL, location VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_3BAE0AA712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, title, description, date, price, location, category_id) SELECT id, title, description, date, price, location, picture_id FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA712469DE2 ON event (category_id)');
        $this->addSql('ALTER TABLE user ADD COLUMN register_date DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE user_category');
        $this->addSql('DROP INDEX IDX_3BAE0AA712469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, category_id, title, description, date, price, location FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, picture_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, date DATETIME NOT NULL, price DOUBLE PRECISION DEFAULT NULL, location VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_3BAE0AA712469DE2 FOREIGN KEY (picture_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, picture_id, title, description, date, price, location) SELECT id, category_id, title, description, date, price, location FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, verified FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, verified BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, verified) SELECT id, email, roles, password, verified FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
