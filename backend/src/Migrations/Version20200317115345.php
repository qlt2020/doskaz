<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317115345 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE blog_comments_id_seq CASCADE');
        $this->addSql('CREATE TABLE user_comments_history (id UUID NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN user_comments_history.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_comments_history.date IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE blog_comments (id UUID NOT NULL, post_id INT NOT NULL, parent_id UUID DEFAULT NULL, user_id INT NOT NULL, text TEXT NOT NULL, popularity INT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN blog_comments.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN blog_comments.parent_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN blog_comments.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE users ALTER full_name TYPE JSONB');
        $this->addSql('ALTER TABLE users ALTER full_name DROP DEFAULT');
        $this->addSql('ALTER TABLE objects_events_history ALTER data TYPE JSONB');
        $this->addSql('ALTER TABLE objects_events_history ALTER data DROP DEFAULT');
        $this->addSql('ALTER TABLE objects ALTER zones TYPE JSONB');
        $this->addSql('ALTER TABLE objects ALTER zones DROP DEFAULT');
        $this->addSql('ALTER TABLE objects ALTER photos TYPE JSONB');
        $this->addSql('ALTER TABLE objects ALTER photos DROP DEFAULT');
        $this->addSql('ALTER TABLE adding_requests ALTER data TYPE JSONB');
        $this->addSql('ALTER TABLE adding_requests ALTER data DROP DEFAULT');
        $this->addSql('ALTER TABLE objects_photos_history ALTER file TYPE JSONB');
        $this->addSql('ALTER TABLE objects_photos_history ALTER file DROP DEFAULT');
        $this->addSql('ALTER TABLE blog_posts ALTER image TYPE JSONB');
        $this->addSql('ALTER TABLE blog_posts ALTER image DROP DEFAULT');
        $this->addSql('ALTER TABLE blog_posts ALTER meta_og_image TYPE JSONB');
        $this->addSql('ALTER TABLE blog_posts ALTER meta_og_image DROP DEFAULT');
        $this->addSql('ALTER TABLE blog_categories ALTER meta TYPE JSONB');
        $this->addSql('ALTER TABLE blog_categories ALTER meta DROP DEFAULT');
        $this->addSql('ALTER TABLE complaints ALTER complainant TYPE JSONB');
        $this->addSql('ALTER TABLE complaints ALTER complainant DROP DEFAULT');
        $this->addSql('ALTER TABLE complaints ALTER content TYPE JSONB');
        $this->addSql('ALTER TABLE complaints ALTER content DROP DEFAULT');
        $this->addSql('ALTER TABLE regional_representatives ALTER image TYPE JSONB');
        $this->addSql('ALTER TABLE regional_representatives ALTER image DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE blog_comments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE user_comments_history');
        $this->addSql('DROP TABLE blog_comments');
        $this->addSql('ALTER TABLE adding_requests ALTER data TYPE JSONB');
        $this->addSql('ALTER TABLE adding_requests ALTER data DROP DEFAULT');
        $this->addSql('ALTER TABLE objects_photos_history ALTER file TYPE JSONB');
        $this->addSql('ALTER TABLE objects_photos_history ALTER file DROP DEFAULT');
        $this->addSql('ALTER TABLE objects_events_history ALTER data TYPE JSONB');
        $this->addSql('ALTER TABLE objects_events_history ALTER data DROP DEFAULT');
        $this->addSql('ALTER TABLE regional_representatives ALTER image TYPE JSONB');
        $this->addSql('ALTER TABLE regional_representatives ALTER image DROP DEFAULT');
        $this->addSql('ALTER TABLE objects ALTER zones TYPE JSONB');
        $this->addSql('ALTER TABLE objects ALTER zones DROP DEFAULT');
        $this->addSql('ALTER TABLE objects ALTER photos TYPE JSONB');
        $this->addSql('ALTER TABLE objects ALTER photos DROP DEFAULT');
        $this->addSql('ALTER TABLE blog_categories ALTER meta TYPE JSONB');
        $this->addSql('ALTER TABLE blog_categories ALTER meta DROP DEFAULT');
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
