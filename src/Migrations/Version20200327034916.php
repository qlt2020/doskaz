<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200327034916 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE daily_tasks ALTER verified_object_id TYPE UUID USING null');
        $this->addSql('ALTER TABLE daily_tasks ALTER verified_object_id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN daily_tasks.verified_object_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE daily_tasks ALTER verified_object_id TYPE INT');
        $this->addSql('ALTER TABLE daily_tasks ALTER verified_object_id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN daily_tasks.verified_object_id IS NULL');
    }
}
