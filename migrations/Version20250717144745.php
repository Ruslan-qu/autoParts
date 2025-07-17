<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250717144745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE original_rooms ADD id_participant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE original_rooms ADD original_manufacturer VARCHAR(45) DEFAULT NULL');
        $this->addSql('ALTER TABLE original_rooms ADD CONSTRAINT FK_B311FA64A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B311FA64A07A8D1F ON original_rooms (id_participant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE original_rooms DROP CONSTRAINT FK_B311FA64A07A8D1F');
        $this->addSql('DROP INDEX IDX_B311FA64A07A8D1F');
        $this->addSql('ALTER TABLE original_rooms DROP id_participant_id');
        $this->addSql('ALTER TABLE original_rooms DROP original_manufacturer');
    }
}
