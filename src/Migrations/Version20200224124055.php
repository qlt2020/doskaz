<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200224124055 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE adding_requests ADD deleted_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN adding_requests.deleted_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE objects ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE objects ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN objects.created_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE adding_requests DROP deleted_at');
        $this->addSql('ALTER TABLE objects ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE objects ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN objects.created_at IS NULL');
    }
}
