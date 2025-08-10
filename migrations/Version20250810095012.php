<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250810095012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE replacing_original_numbers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE replacing_original_numbers (id INT NOT NULL, id_original_number_id INT DEFAULT NULL, replacing_original_number VARCHAR(33) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C3776F6D91013AA ON replacing_original_numbers (id_original_number_id)');
        $this->addSql('ALTER TABLE replacing_original_numbers ADD CONSTRAINT FK_5C3776F6D91013AA FOREIGN KEY (id_original_number_id) REFERENCES original_rooms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE original_rooms DROP replacing_original_number');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE replacing_original_numbers_id_seq CASCADE');
        $this->addSql('ALTER TABLE replacing_original_numbers DROP CONSTRAINT FK_5C3776F6D91013AA');
        $this->addSql('DROP TABLE replacing_original_numbers');
        $this->addSql('ALTER TABLE original_rooms ADD replacing_original_number JSON DEFAULT NULL');
    }
}
