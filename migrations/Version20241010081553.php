<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010081553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP CONSTRAINT fk_fde94543e37d77a5');
        $this->addSql('DROP INDEX idx_fde94543e37d77a5');
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP id_manufacturer_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD id_manufacturer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD CONSTRAINT fk_fde94543e37d77a5 FOREIGN KEY (id_manufacturer_id) REFERENCES part_numbers_from_manufacturers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_fde94543e37d77a5 ON auto_parts_warehouse (id_manufacturer_id)');
    }
}
