<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190517154425 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_3BAE0AA712469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, category_id, title, description, date, price, location FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, date DATETIME NOT NULL, price DOUBLE PRECISION DEFAULT NULL, location VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_3BAE0AA712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, category_id, title, description, date, price, location) SELECT id, category_id, title, description, date, price, location FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA712469DE2 ON event (category_id)');
        $this->addSql('DROP INDEX IDX_E6C1FDC112469DE2');
        $this->addSql('DROP INDEX IDX_E6C1FDC1A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_category AS SELECT user_id, category_id FROM user_category');
        $this->addSql('DROP TABLE user_category');
        $this->addSql('CREATE TABLE user_category (user_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(user_id, category_id), CONSTRAINT FK_E6C1FDC1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E6C1FDC112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_category (user_id, category_id) SELECT user_id, category_id FROM __temp__user_category');
        $this->addSql('DROP TABLE __temp__user_category');
        $this->addSql('CREATE INDEX IDX_E6C1FDC112469DE2 ON user_category (category_id)');
        $this->addSql('CREATE INDEX IDX_E6C1FDC1A76ED395 ON user_category (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_3BAE0AA712469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, category_id, title, description, date, price, location FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, date DATETIME NOT NULL, price DOUBLE PRECISION DEFAULT NULL, location VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO event (id, category_id, title, description, date, price, location) SELECT id, category_id, title, description, date, price, location FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA712469DE2 ON event (category_id)');
        $this->addSql('DROP INDEX IDX_E6C1FDC1A76ED395');
        $this->addSql('DROP INDEX IDX_E6C1FDC112469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_category AS SELECT user_id, category_id FROM user_category');
        $this->addSql('DROP TABLE user_category');
        $this->addSql('CREATE TABLE user_category (user_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(user_id, category_id))');
        $this->addSql('INSERT INTO user_category (user_id, category_id) SELECT user_id, category_id FROM __temp__user_category');
        $this->addSql('DROP TABLE __temp__user_category');
        $this->addSql('CREATE INDEX IDX_E6C1FDC1A76ED395 ON user_category (user_id)');
        $this->addSql('CREATE INDEX IDX_E6C1FDC112469DE2 ON user_category (category_id)');
    }
}
