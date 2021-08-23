<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200220125954 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE objects ADD overall_score_movement VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE objects ADD overall_score_limb VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE objects ADD overall_score_vision VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE objects ADD overall_score_hearing VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE objects ADD overall_score_intellectual VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE objects DROP overall_score_movement');
        $this->addSql('ALTER TABLE objects DROP overall_score_limb');
        $this->addSql('ALTER TABLE objects DROP overall_score_vision');
        $this->addSql('ALTER TABLE objects DROP overall_score_hearing');
        $this->addSql('ALTER TABLE objects DROP overall_score_intellectual');
    }
}
