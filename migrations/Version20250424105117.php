<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424105117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE part_name ADD id_participant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_name ADD CONSTRAINT FK_248B008BA07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_248B008BA07A8D1F ON part_name (id_participant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE part_name DROP CONSTRAINT FK_248B008BA07A8D1F');
        $this->addSql('DROP INDEX IDX_248B008BA07A8D1F');
        $this->addSql('ALTER TABLE part_name DROP id_participant_id');
    }
}
