<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210825120021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE blog_categories ADD title_kz TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_categories ADD title_en TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_posts ADD title_kz TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_posts ADD title_en TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_posts ADD annotation_kz TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_posts ADD annotation_en TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_posts ADD content_kz TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_posts ADD content_en TEXT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B21ACCF3D17F50A6 ON objects (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B21ACCF3427EB8A5 ON objects (request_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA tiger');
        $this->addSql('CREATE SCHEMA tiger_data');
        $this->addSql('CREATE SCHEMA topology');
        $this->addSql('ALTER TABLE blog_categories DROP title_kz');
        $this->addSql('ALTER TABLE blog_categories DROP title_en');
        $this->addSql('ALTER TABLE blog_posts DROP title_kz');
        $this->addSql('ALTER TABLE blog_posts DROP title_en');
        $this->addSql('ALTER TABLE blog_posts DROP annotation_kz');
        $this->addSql('ALTER TABLE blog_posts DROP annotation_en');
        $this->addSql('ALTER TABLE blog_posts DROP content_kz');
        $this->addSql('ALTER TABLE blog_posts DROP content_en');
        $this->addSql('DROP INDEX UNIQ_B21ACCF3D17F50A6');
        $this->addSql('DROP INDEX UNIQ_B21ACCF3427EB8A5');
    }
}
