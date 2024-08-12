<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240812180228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE availability_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE axles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bodies_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE car_brands_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE original_rooms_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE part_name_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sides_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE availability (id INT NOT NULL, in_stock VARCHAR(28) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE axles (id INT NOT NULL, axle VARCHAR(12) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE bodies (id INT NOT NULL, body VARCHAR(12) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE car_brands (id INT NOT NULL, car_brand VARCHAR(24) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE original_rooms (id INT NOT NULL, original_number VARCHAR(48) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE part_name (id INT NOT NULL, part_name VARCHAR(48) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sides (id INT NOT NULL, side VARCHAR(28) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD id_part_name_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD id_car_brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD id_side_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD id_body_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD id_axle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD id_in_stock_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD id_original_number_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD CONSTRAINT FK_2675D15CD28A1EAF FOREIGN KEY (id_part_name_id) REFERENCES part_name (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD CONSTRAINT FK_2675D15CE802907E FOREIGN KEY (id_car_brand_id) REFERENCES car_brands (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD CONSTRAINT FK_2675D15C48C028B4 FOREIGN KEY (id_side_id) REFERENCES sides (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD CONSTRAINT FK_2675D15C45FFB4F4 FOREIGN KEY (id_body_id) REFERENCES bodies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD CONSTRAINT FK_2675D15C407B3FAC FOREIGN KEY (id_axle_id) REFERENCES axles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD CONSTRAINT FK_2675D15C5226D0BB FOREIGN KEY (id_in_stock_id) REFERENCES availability (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD CONSTRAINT FK_2675D15CD91013AA FOREIGN KEY (id_original_number_id) REFERENCES original_rooms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2675D15CD28A1EAF ON part_numbers_from_manufacturers (id_part_name_id)');
        $this->addSql('CREATE INDEX IDX_2675D15CE802907E ON part_numbers_from_manufacturers (id_car_brand_id)');
        $this->addSql('CREATE INDEX IDX_2675D15C48C028B4 ON part_numbers_from_manufacturers (id_side_id)');
        $this->addSql('CREATE INDEX IDX_2675D15C45FFB4F4 ON part_numbers_from_manufacturers (id_body_id)');
        $this->addSql('CREATE INDEX IDX_2675D15C407B3FAC ON part_numbers_from_manufacturers (id_axle_id)');
        $this->addSql('CREATE INDEX IDX_2675D15C5226D0BB ON part_numbers_from_manufacturers (id_in_stock_id)');
        $this->addSql('CREATE INDEX IDX_2675D15CD91013AA ON part_numbers_from_manufacturers (id_original_number_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP CONSTRAINT FK_2675D15C5226D0BB');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP CONSTRAINT FK_2675D15C407B3FAC');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP CONSTRAINT FK_2675D15C45FFB4F4');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP CONSTRAINT FK_2675D15CE802907E');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP CONSTRAINT FK_2675D15CD91013AA');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP CONSTRAINT FK_2675D15CD28A1EAF');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP CONSTRAINT FK_2675D15C48C028B4');
        $this->addSql('DROP SEQUENCE availability_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE axles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bodies_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE car_brands_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE original_rooms_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE part_name_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sides_id_seq CASCADE');
        $this->addSql('DROP TABLE availability');
        $this->addSql('DROP TABLE axles');
        $this->addSql('DROP TABLE bodies');
        $this->addSql('DROP TABLE car_brands');
        $this->addSql('DROP TABLE original_rooms');
        $this->addSql('DROP TABLE part_name');
        $this->addSql('DROP TABLE sides');
        $this->addSql('DROP INDEX IDX_2675D15CD28A1EAF');
        $this->addSql('DROP INDEX IDX_2675D15CE802907E');
        $this->addSql('DROP INDEX IDX_2675D15C48C028B4');
        $this->addSql('DROP INDEX IDX_2675D15C45FFB4F4');
        $this->addSql('DROP INDEX IDX_2675D15C407B3FAC');
        $this->addSql('DROP INDEX IDX_2675D15C5226D0BB');
        $this->addSql('DROP INDEX IDX_2675D15CD91013AA');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP id_part_name_id');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP id_car_brand_id');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP id_side_id');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP id_body_id');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP id_axle_id');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP id_in_stock_id');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP id_original_number_id');
    }
}
