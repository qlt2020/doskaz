<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200327035654 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE daily_tasks ALTER added_object_id TYPE UUID using null');
        $this->addSql('ALTER TABLE daily_tasks ALTER added_object_id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN daily_tasks.added_object_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE daily_tasks ALTER added_object_id TYPE INT');
        $this->addSql('ALTER TABLE daily_tasks ALTER added_object_id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN daily_tasks.added_object_id IS NULL');
    }
}
