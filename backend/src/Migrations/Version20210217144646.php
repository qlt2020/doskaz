<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210217144646 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE object_photos_adding_requests (id UUID NOT NULL, object_id INT NOT NULL, created_by INT NOT NULL, approved_by INT DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, approved_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, status VARCHAR(255) NOT NULL, photos JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN object_photos_adding_requests.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN object_photos_adding_requests.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN object_photos_adding_requests.approved_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE object_photos_adding_requests');
    }
}
