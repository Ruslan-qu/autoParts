<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808133252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE counterparty_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE part_numbers_from_manufacturers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_method_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE counterparty (id INT NOT NULL, counterparty VARCHAR(28) DEFAULT NULL, mail_counterparty VARCHAR(48) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE part_numbers_from_manufacturers (id INT NOT NULL, part_number VARCHAR(23) DEFAULT NULL, manufacturer VARCHAR(23) DEFAULT NULL, additional_descriptions TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE payment_method (id INT NOT NULL, method VARCHAR(13) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE counterparty_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE part_numbers_from_manufacturers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_method_id_seq CASCADE');
        $this->addSql('DROP TABLE counterparty');
        $this->addSql('DROP TABLE part_numbers_from_manufacturers');
        $this->addSql('DROP TABLE payment_method');
    }
}
