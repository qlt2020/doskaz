<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191123104418 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE complaints_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE complaints (id INT NOT NULL, complainant JSONB NOT NULL, authority_id INT NOT NULL, complainant_id INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, content JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN complaints.created_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE complaints_id_seq CASCADE');
        $this->addSql('DROP TABLE complaints');
    }
}
