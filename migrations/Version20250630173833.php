<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250630173833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sides ADD id_participant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sides ADD CONSTRAINT FK_A0260913A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A0260913A07A8D1F ON sides (id_participant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sides DROP CONSTRAINT FK_A0260913A07A8D1F');
        $this->addSql('DROP INDEX IDX_A0260913A07A8D1F');
        $this->addSql('ALTER TABLE sides DROP id_participant_id');
    }
}
