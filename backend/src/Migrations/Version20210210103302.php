<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210103302 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql("
             update objects set zones = jsonb_set(zones, '{kidsAccessibility}', '{
                \"type\": \"kidsAccessibility_small\",
                \"attributes\": {
                    \"attribute1\": \"unknown\"
                },
                \"overriddenScore\": null
            }') where zones->>'type' = 'small'
        ");

        $this->addSql("
             update objects set zones = jsonb_set(zones, '{kidsAccessibility}', '{
                \"type\": \"kidsAccessibility_middle\",
                \"attributes\": {
                    \"attribute1\": \"unknown\",
            \"attribute2\": \"unknown\",
            \"attribute3\": \"unknown\",
            \"attribute4\": \"unknown\",
            \"attribute5\": \"unknown\",
            \"attribute6\": \"unknown\",
            \"attribute7\": \"unknown\",
            \"attribute8\": \"unknown\",
            \"attribute9\": \"unknown\",
            \"attribute10\": \"unknown\"
                },
                \"overriddenScore\": null
            }') where zones->>'type' = 'middle'
        ");

        $this->addSql("
             update objects set zones = jsonb_set(zones, '{kidsAccessibility}', '{
                \"type\": \"kidsAccessibility_full\",
                \"attributes\": {
                    \"attribute1\": \"unknown\",
            \"attribute2\": \"unknown\",
            \"attribute3\": \"unknown\",
            \"attribute4\": \"unknown\",
            \"attribute5\": \"unknown\",
            \"attribute6\": \"unknown\",
            \"attribute7\": \"unknown\",
            \"attribute8\": \"unknown\",
            \"attribute9\": \"unknown\",
            \"attribute10\": \"unknown\"
                },
                \"overriddenScore\": null
            }') where zones->>'type' = 'full'
        ");


        $this->addSql("
             update adding_requests set data = jsonb_set(data, '{kidsAccessibility}', '{
                \"attributes\": {
                    \"attribute1\": \"unknown\",
            \"attribute2\": \"unknown\",
            \"attribute3\": \"unknown\",
            \"attribute4\": \"unknown\",
            \"attribute5\": \"unknown\",
            \"attribute6\": \"unknown\",
            \"attribute7\": \"unknown\",
            \"attribute8\": \"unknown\",
            \"attribute9\": \"unknown\",
            \"attribute10\": \"unknown\"
                },
                \"overriddenScore\": null
            }')
        ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
    }
}
