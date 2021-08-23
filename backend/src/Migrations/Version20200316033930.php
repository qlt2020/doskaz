<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200316033930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE regional_representatives (id UUID NOT NULL, city_id INT NOT NULL, name TEXT NOT NULL, email TEXT NOT NULL, phone TEXT NOT NULL, department TEXT NOT NULL, image JSONB DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN regional_representatives.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN regional_representatives.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN regional_representatives.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN regional_representatives.deleted_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE users ALTER full_name TYPE JSONB');
        $this->addSql('ALTER TABLE users ALTER full_name DROP DEFAULT');
        $this->addSql('ALTER TABLE objects ALTER zones TYPE JSONB');
        $this->addSql('ALTER TABLE objects ALTER zones DROP DEFAULT');
        $this->addSql('ALTER TABLE objects ALTER photos TYPE JSONB');
        $this->addSql('ALTER TABLE objects ALTER photos DROP DEFAULT');
        $this->addSql('ALTER TABLE adding_requests ALTER data TYPE JSONB');
        $this->addSql('ALTER TABLE adding_requests ALTER data DROP DEFAULT');
        $this->addSql('ALTER TABLE objects_events_history ALTER data TYPE JSONB');
        $this->addSql('ALTER TABLE objects_events_history ALTER data DROP DEFAULT');
        $this->addSql('ALTER TABLE objects_photos_history ALTER file TYPE JSONB');
        $this->addSql('ALTER TABLE objects_photos_history ALTER file DROP DEFAULT');
        $this->addSql('ALTER TABLE blog_categories ALTER meta TYPE JSONB');
        $this->addSql('ALTER TABLE blog_categories ALTER meta DROP DEFAULT');
        $this->addSql('ALTER TABLE blog_posts ALTER image TYPE JSONB');
        $this->addSql('ALTER TABLE blog_posts ALTER image DROP DEFAULT');
        $this->addSql('ALTER TABLE blog_posts ALTER meta_og_image TYPE JSONB');
        $this->addSql('ALTER TABLE blog_posts ALTER meta_og_image DROP DEFAULT');
        $this->addSql('ALTER TABLE complaints ALTER complainant TYPE JSONB');
        $this->addSql('ALTER TABLE complaints ALTER complainant DROP DEFAULT');
        $this->addSql('ALTER TABLE complaints ALTER content TYPE JSONB');
        $this->addSql('ALTER TABLE complaints ALTER content DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE regional_representatives');
        $this->addSql('ALTER TABLE adding_requests ALTER data TYPE JSONB');
        $this->addSql('ALTER TABLE adding_requests ALTER data DROP DEFAULT');
        $this->addSql('ALTER TABLE objects_photos_history ALTER file TYPE JSONB');
        $this->addSql('ALTER TABLE objects_photos_history ALTER file DROP DEFAULT');
        $this->addSql('ALTER TABLE objects_events_history ALTER data TYPE JSONB');
        $this->addSql('ALTER TABLE objects_events_history ALTER data DROP DEFAULT');
        $this->addSql('ALTER TABLE blog_categories ALTER meta TYPE JSONB');
        $this->addSql('ALTER TABLE blog_categories ALTER meta DROP DEFAULT');
        $this->addSql('ALTER TABLE objects ALTER zones TYPE JSONB');
        $this->addSql('ALTER TABLE objects ALTER zones DROP DEFAULT');
        $this->addSql('ALTER TABLE objects ALTER photos TYPE JSONB');
        $this->addSql('ALTER TABLE objects ALTER photos DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER full_name TYPE JSONB');
        $this->addSql('ALTER TABLE users ALTER full_name DROP DEFAULT');
        $this->addSql('ALTER TABLE complaints ALTER complainant TYPE JSONB');
        $this->addSql('ALTER TABLE complaints ALTER complainant DROP DEFAULT');
        $this->addSql('ALTER TABLE complaints ALTER content TYPE JSONB');
        $this->addSql('ALTER TABLE complaints ALTER content DROP DEFAULT');
        $this->addSql('ALTER TABLE blog_posts ALTER image TYPE JSONB');
        $this->addSql('ALTER TABLE blog_posts ALTER image DROP DEFAULT');
        $this->addSql('ALTER TABLE blog_posts ALTER meta_og_image TYPE JSONB');
        $this->addSql('ALTER TABLE blog_posts ALTER meta_og_image DROP DEFAULT');
    }
}
