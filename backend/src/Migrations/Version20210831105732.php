<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831105732 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE users ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD gender VARCHAR(1) DEFAULT \'u\' NOT NULL');
        $this->addSql('ALTER TABLE users ADD category VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD birthday DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E98BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1483A5E98BAC62AF ON users (city_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E98BAC62AF');
        $this->addSql('DROP INDEX IDX_1483A5E98BAC62AF');
        $this->addSql('ALTER TABLE users DROP city_id');
        $this->addSql('ALTER TABLE users DROP gender');
        $this->addSql('ALTER TABLE users DROP category');
        $this->addSql('ALTER TABLE users DROP birthday');
    }
}
