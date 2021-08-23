<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191111084840 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE blog_posts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE blog_posts (id INT NOT NULL, title TEXT NOT NULL, category_id INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, published_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, is_published BOOLEAN NOT NULL, deleted_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, slug_value TEXT NOT NULL, meta_title TEXT DEFAULT NULL, meta_description TEXT DEFAULT NULL, meta_keywords TEXT DEFAULT NULL, meta_og_title TEXT DEFAULT NULL, meta_og_description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_78B2F932C88FDCC9 ON blog_posts (slug_value)');
        $this->addSql('COMMENT ON COLUMN blog_posts.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN blog_posts.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN blog_posts.published_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN blog_posts.deleted_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE blog_posts_id_seq CASCADE');
        $this->addSql('DROP TABLE blog_posts');
    }
}
