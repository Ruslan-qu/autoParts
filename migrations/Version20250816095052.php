<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250816095052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD id_participant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD CONSTRAINT FK_2675D15CA07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2675D15CA07A8D1F ON part_numbers_from_manufacturers (id_participant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP CONSTRAINT FK_2675D15CA07A8D1F');
        $this->addSql('DROP INDEX IDX_2675D15CA07A8D1F');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP id_participant_id');
    }
}
