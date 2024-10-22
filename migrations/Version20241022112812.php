<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022112812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE auto_parts_sold_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE auto_parts_sold (id INT NOT NULL, id_auto_parts_warehouse_id INT DEFAULT NULL, quantity_sold INT DEFAULT NULL, price_sold INT DEFAULT NULL, date_sold DATE DEFAULT NULL, id_sold INT DEFAULT NULL, sold_status BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C77DB172E2EC098 ON auto_parts_sold (id_auto_parts_warehouse_id)');
        $this->addSql('COMMENT ON COLUMN auto_parts_sold.date_sold IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE auto_parts_sold ADD CONSTRAINT FK_4C77DB172E2EC098 FOREIGN KEY (id_auto_parts_warehouse_id) REFERENCES auto_parts_warehouse (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE auto_parts_sold_id_seq CASCADE');
        $this->addSql('ALTER TABLE auto_parts_sold DROP CONSTRAINT FK_4C77DB172E2EC098');
        $this->addSql('DROP TABLE auto_parts_sold');
    }
}
