<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200224072622 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE objects ALTER overall_score_movement DROP NOT NULL');
        $this->addSql('ALTER TABLE objects ALTER overall_score_limb DROP NOT NULL');
        $this->addSql('ALTER TABLE objects ALTER overall_score_vision DROP NOT NULL');
        $this->addSql('ALTER TABLE objects ALTER overall_score_hearing DROP NOT NULL');
        $this->addSql('ALTER TABLE objects ALTER overall_score_intellectual DROP NOT NULL');
        $this->addSql('ALTER TABLE adding_requests ADD approved_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN adding_requests.approved_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE adding_requests DROP approved_at');
        $this->addSql('ALTER TABLE objects ALTER overall_score_movement SET NOT NULL');
        $this->addSql('ALTER TABLE objects ALTER overall_score_limb SET NOT NULL');
        $this->addSql('ALTER TABLE objects ALTER overall_score_vision SET NOT NULL');
        $this->addSql('ALTER TABLE objects ALTER overall_score_hearing SET NOT NULL');
        $this->addSql('ALTER TABLE objects ALTER overall_score_intellectual SET NOT NULL');
    }
}
