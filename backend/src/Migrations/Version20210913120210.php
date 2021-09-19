<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913120210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE help_categories (id INT NOT NULL, name TEXT NOT NULL, name_kz TEXT NOT NULL, name_en TEXT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D0268E6A5E237E06 ON help_categories (name)');
        $this->addSql('COMMENT ON COLUMN help_categories.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN help_categories.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN help_categories.deleted_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE helps (id INT NOT NULL, category_id INT NOT NULL, title TEXT NOT NULL, title_kz TEXT NOT NULL, title_en TEXT NOT NULL, description TEXT NOT NULL, description_kz TEXT NOT NULL, description_en TEXT NOT NULL, image TEXT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, is_published BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C466A79412469DE2 ON helps (category_id)');
        $this->addSql('COMMENT ON COLUMN helps.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN helps.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN helps.deleted_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE helps ADD CONSTRAINT FK_C466A79412469DE2 FOREIGN KEY (category_id) REFERENCES help_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA tiger');
        $this->addSql('CREATE SCHEMA tiger_data');
        $this->addSql('CREATE SCHEMA topology');
        $this->addSql('ALTER TABLE helps DROP CONSTRAINT FK_C466A79412469DE2');
        $this->addSql('DROP TABLE help_categories');
        $this->addSql('DROP TABLE helps');
    }
}
