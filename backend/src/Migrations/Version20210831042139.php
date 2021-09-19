<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831042139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE levels_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE profile_completion_tasks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE adding_requests ADD CONSTRAINT FK_40035E27A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_40035E27A76ED395 ON adding_requests (user_id)');
        $this->addSql('ALTER TABLE administration_tasks ADD CONSTRAINT FK_EF0453C58BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EF0453C58BAC62AF ON administration_tasks (city_id)');
        $this->addSql('ALTER TABLE awards ADD CONSTRAINT FK_25EAE3FEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE awards ADD CONSTRAINT FK_25EAE3FE4E1DD2BF FOREIGN KEY (issued_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_25EAE3FEA76ED395 ON awards (user_id)');
        $this->addSql('CREATE INDEX IDX_25EAE3FE4E1DD2BF ON awards (issued_by)');
        $this->addSql('ALTER TABLE blog_comments ADD CONSTRAINT FK_2BC3B20D4B89032C FOREIGN KEY (post_id) REFERENCES blog_posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blog_comments ADD CONSTRAINT FK_2BC3B20D727ACA70 FOREIGN KEY (parent_id) REFERENCES blog_comments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blog_comments ADD CONSTRAINT FK_2BC3B20DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2BC3B20D4B89032C ON blog_comments (post_id)');
        $this->addSql('CREATE INDEX IDX_2BC3B20D727ACA70 ON blog_comments (parent_id)');
        $this->addSql('CREATE INDEX IDX_2BC3B20DA76ED395 ON blog_comments (user_id)');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F93212469DE2 FOREIGN KEY (category_id) REFERENCES blog_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_78B2F93212469DE2 ON blog_posts (category_id)');
        $this->addSql('ALTER TABLE complaints ADD CONSTRAINT FK_A05AAF3A81EC865B FOREIGN KEY (authority_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaints ADD CONSTRAINT FK_A05AAF3A4C422040 FOREIGN KEY (complainant_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaints ADD CONSTRAINT FK_A05AAF3A232D562B FOREIGN KEY (object_id) REFERENCES objects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A05AAF3A81EC865B ON complaints (authority_id)');
        $this->addSql('CREATE INDEX IDX_A05AAF3A4C422040 ON complaints (complainant_id)');
        $this->addSql('CREATE INDEX IDX_A05AAF3A232D562B ON complaints (object_id)');
        $this->addSql('ALTER TABLE daily_tasks ADD CONSTRAINT FK_D0790443A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE daily_tasks ADD CONSTRAINT FK_D0790443232D562B FOREIGN KEY (object_id) REFERENCES objects (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D0790443A76ED395 ON daily_tasks (user_id)');
        $this->addSql('CREATE INDEX IDX_D0790443232D562B ON daily_tasks (object_id)');
        $this->addSql('ALTER TABLE daily_verification_tasks ADD CONSTRAINT FK_B51AC546A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE daily_verification_tasks ADD CONSTRAINT FK_B51AC546232D562B FOREIGN KEY (object_id) REFERENCES objects (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B51AC546A76ED395 ON daily_verification_tasks (user_id)');
        $this->addSql('CREATE INDEX IDX_B51AC546232D562B ON daily_verification_tasks (object_id)');
        $this->addSql('ALTER TABLE levels ADD id SERIAL NOT NULL');
        $this->addSql('ALTER TABLE levels ADD CONSTRAINT FK_9F2A6419A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9F2A6419A76ED395 ON levels (user_id)');
        $this->addSql('ALTER TABLE levels ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE object_photos_adding_requests ADD CONSTRAINT FK_324AB09F232D562B FOREIGN KEY (object_id) REFERENCES objects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE object_photos_adding_requests ADD CONSTRAINT FK_324AB09FDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE object_photos_adding_requests ADD CONSTRAINT FK_324AB09F4EA3CB3D FOREIGN KEY (approved_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_324AB09F232D562B ON object_photos_adding_requests (object_id)');
        $this->addSql('CREATE INDEX IDX_324AB09FDE12AB56 ON object_photos_adding_requests (created_by)');
        $this->addSql('CREATE INDEX IDX_324AB09F4EA3CB3D ON object_photos_adding_requests (approved_by)');
        $this->addSql('ALTER TABLE object_reviews ADD CONSTRAINT FK_693E6BAF232D562B FOREIGN KEY (object_id) REFERENCES objects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE object_reviews ADD CONSTRAINT FK_693E6BAFF675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_693E6BAF232D562B ON object_reviews (object_id)');
        $this->addSql('CREATE INDEX IDX_693E6BAFF675F31B ON object_reviews (author_id)');
        $this->addSql('ALTER TABLE object_verifications ADD CONSTRAINT FK_5FB76934A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5FB76934A76ED395 ON object_verifications (user_id)');
        $this->addSql('ALTER TABLE objects ADD CONSTRAINT FK_B21ACCF312469DE2 FOREIGN KEY (category_id) REFERENCES object_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE objects ADD CONSTRAINT FK_B21ACCF3427EB8A5 FOREIGN KEY (request_id) REFERENCES adding_requests (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE objects ADD CONSTRAINT FK_B21ACCF3DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B21ACCF312469DE2 ON objects (category_id)');
        $this->addSql('CREATE INDEX IDX_B21ACCF3DE12AB56 ON objects (created_by)');
        $this->addSql('ALTER TABLE objects_events_history ADD CONSTRAINT FK_AB69A09232D562B FOREIGN KEY (object_id) REFERENCES objects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE objects_events_history ADD CONSTRAINT FK_AB69A09A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB69A09232D562B ON objects_events_history (object_id)');
        $this->addSql('CREATE INDEX IDX_AB69A09A76ED395 ON objects_events_history (user_id)');
        $this->addSql('ALTER TABLE objects_photos_history ADD CONSTRAINT FK_DF666C53232D562B FOREIGN KEY (object_id) REFERENCES objects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE objects_photos_history ADD CONSTRAINT FK_DF666C53A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DF666C53232D562B ON objects_photos_history (object_id)');
        $this->addSql('CREATE INDEX IDX_DF666C53A76ED395 ON objects_photos_history (user_id)');
        $this->addSql('ALTER TABLE profile_completion_tasks ADD id SERIAL NOT NULL');
        $this->addSql('ALTER TABLE profile_completion_tasks ADD CONSTRAINT FK_AD85C39A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AD85C39A76ED395 ON profile_completion_tasks (user_id)');
        $this->addSql('ALTER TABLE profile_completion_tasks ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE profile_notifications ADD CONSTRAINT FK_4E031513A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4E031513A76ED395 ON profile_notifications (user_id)');
        $this->addSql('ALTER TABLE regional_coordinators ADD CONSTRAINT FK_A5E35D6BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE regional_representatives ADD CONSTRAINT FK_906F4A438BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_906F4A438BAC62AF ON regional_representatives (city_id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865978BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_505865978BAC62AF ON tasks (city_id)');
        $this->addSql('ALTER TABLE user_comments_history ADD CONSTRAINT FK_5766C15AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5766C15AA76ED395 ON user_comments_history (user_id)');
        $this->addSql('ALTER TABLE user_events ADD CONSTRAINT FK_36D54C77A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_36D54C77A76ED395 ON user_events (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA tiger');
        $this->addSql('CREATE SCHEMA tiger_data');
        $this->addSql('CREATE SCHEMA topology');
        $this->addSql('DROP SEQUENCE levels_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE profile_completion_tasks_id_seq CASCADE');
        $this->addSql('ALTER TABLE adding_requests DROP CONSTRAINT FK_40035E27A76ED395');
        $this->addSql('DROP INDEX IDX_40035E27A76ED395');
        $this->addSql('ALTER TABLE administration_tasks DROP CONSTRAINT FK_EF0453C58BAC62AF');
        $this->addSql('DROP INDEX IDX_EF0453C58BAC62AF');
        $this->addSql('ALTER TABLE awards DROP CONSTRAINT FK_25EAE3FEA76ED395');
        $this->addSql('ALTER TABLE awards DROP CONSTRAINT FK_25EAE3FE4E1DD2BF');
        $this->addSql('DROP INDEX IDX_25EAE3FEA76ED395');
        $this->addSql('DROP INDEX IDX_25EAE3FE4E1DD2BF');
        $this->addSql('ALTER TABLE blog_comments DROP CONSTRAINT FK_2BC3B20D4B89032C');
        $this->addSql('ALTER TABLE blog_comments DROP CONSTRAINT FK_2BC3B20D727ACA70');
        $this->addSql('ALTER TABLE blog_comments DROP CONSTRAINT FK_2BC3B20DA76ED395');
        $this->addSql('DROP INDEX IDX_2BC3B20D4B89032C');
        $this->addSql('DROP INDEX IDX_2BC3B20D727ACA70');
        $this->addSql('DROP INDEX IDX_2BC3B20DA76ED395');
        $this->addSql('ALTER TABLE complaints DROP CONSTRAINT FK_A05AAF3A81EC865B');
        $this->addSql('ALTER TABLE complaints DROP CONSTRAINT FK_A05AAF3A4C422040');
        $this->addSql('ALTER TABLE complaints DROP CONSTRAINT FK_A05AAF3A232D562B');
        $this->addSql('DROP INDEX IDX_A05AAF3A81EC865B');
        $this->addSql('DROP INDEX IDX_A05AAF3A4C422040');
        $this->addSql('DROP INDEX IDX_A05AAF3A232D562B');
        $this->addSql('ALTER TABLE daily_tasks DROP CONSTRAINT FK_D0790443A76ED395');
        $this->addSql('ALTER TABLE daily_tasks DROP CONSTRAINT FK_D0790443232D562B');
        $this->addSql('DROP INDEX IDX_D0790443A76ED395');
        $this->addSql('DROP INDEX IDX_D0790443232D562B');
        $this->addSql('ALTER TABLE daily_verification_tasks DROP CONSTRAINT FK_B51AC546A76ED395');
        $this->addSql('ALTER TABLE daily_verification_tasks DROP CONSTRAINT FK_B51AC546232D562B');
        $this->addSql('DROP INDEX IDX_B51AC546A76ED395');
        $this->addSql('DROP INDEX IDX_B51AC546232D562B');
        $this->addSql('ALTER TABLE levels DROP CONSTRAINT FK_9F2A6419A76ED395');
        $this->addSql('DROP INDEX UNIQ_9F2A6419A76ED395');
        $this->addSql('DROP INDEX levels_pkey');
        $this->addSql('ALTER TABLE levels DROP id');
        $this->addSql('ALTER TABLE levels ADD PRIMARY KEY (user_id)');
        $this->addSql('ALTER TABLE object_photos_adding_requests DROP CONSTRAINT FK_324AB09F232D562B');
        $this->addSql('ALTER TABLE object_photos_adding_requests DROP CONSTRAINT FK_324AB09FDE12AB56');
        $this->addSql('ALTER TABLE object_photos_adding_requests DROP CONSTRAINT FK_324AB09F4EA3CB3D');
        $this->addSql('DROP INDEX IDX_324AB09F232D562B');
        $this->addSql('DROP INDEX IDX_324AB09FDE12AB56');
        $this->addSql('DROP INDEX IDX_324AB09F4EA3CB3D');
        $this->addSql('ALTER TABLE object_reviews DROP CONSTRAINT FK_693E6BAF232D562B');
        $this->addSql('ALTER TABLE object_reviews DROP CONSTRAINT FK_693E6BAFF675F31B');
        $this->addSql('DROP INDEX IDX_693E6BAF232D562B');
        $this->addSql('DROP INDEX IDX_693E6BAFF675F31B');
        $this->addSql('ALTER TABLE object_verifications DROP CONSTRAINT FK_5FB76934A76ED395');
        $this->addSql('DROP INDEX IDX_5FB76934A76ED395');
        $this->addSql('ALTER TABLE objects DROP CONSTRAINT FK_B21ACCF312469DE2');
        $this->addSql('ALTER TABLE objects DROP CONSTRAINT FK_B21ACCF3427EB8A5');
        $this->addSql('ALTER TABLE objects DROP CONSTRAINT FK_B21ACCF3DE12AB56');
        $this->addSql('DROP INDEX IDX_B21ACCF312469DE2');
        $this->addSql('DROP INDEX IDX_B21ACCF3DE12AB56');
        $this->addSql('ALTER TABLE objects_events_history DROP CONSTRAINT FK_AB69A09232D562B');
        $this->addSql('ALTER TABLE objects_events_history DROP CONSTRAINT FK_AB69A09A76ED395');
        $this->addSql('DROP INDEX IDX_AB69A09232D562B');
        $this->addSql('DROP INDEX IDX_AB69A09A76ED395');
        $this->addSql('ALTER TABLE objects_photos_history DROP CONSTRAINT FK_DF666C53232D562B');
        $this->addSql('ALTER TABLE objects_photos_history DROP CONSTRAINT FK_DF666C53A76ED395');
        $this->addSql('DROP INDEX IDX_DF666C53232D562B');
        $this->addSql('DROP INDEX IDX_DF666C53A76ED395');
        $this->addSql('ALTER TABLE profile_completion_tasks DROP CONSTRAINT FK_AD85C39A76ED395');
        $this->addSql('DROP INDEX IDX_AD85C39A76ED395');
        $this->addSql('DROP INDEX profile_completion_tasks_pkey');
        $this->addSql('ALTER TABLE profile_completion_tasks DROP id');
        $this->addSql('ALTER TABLE profile_completion_tasks ADD PRIMARY KEY (user_id)');
        $this->addSql('ALTER TABLE profile_notifications DROP CONSTRAINT FK_4E031513A76ED395');
        $this->addSql('DROP INDEX IDX_4E031513A76ED395');
        $this->addSql('ALTER TABLE regional_coordinators DROP CONSTRAINT FK_A5E35D6BA76ED395');
        $this->addSql('ALTER TABLE regional_representatives DROP CONSTRAINT FK_906F4A438BAC62AF');
        $this->addSql('DROP INDEX IDX_906F4A438BAC62AF');
        $this->addSql('ALTER TABLE tasks DROP CONSTRAINT FK_505865978BAC62AF');
        $this->addSql('DROP INDEX IDX_505865978BAC62AF');
        $this->addSql('ALTER TABLE user_comments_history DROP CONSTRAINT FK_5766C15AA76ED395');
        $this->addSql('DROP INDEX IDX_5766C15AA76ED395');
        $this->addSql('ALTER TABLE user_events DROP CONSTRAINT FK_36D54C77A76ED395');
        $this->addSql('DROP INDEX IDX_36D54C77A76ED395');
        $this->addSql('ALTER TABLE blog_posts DROP CONSTRAINT FK_78B2F93212469DE2');
        $this->addSql('DROP INDEX IDX_78B2F93212469DE2');
    }
}
