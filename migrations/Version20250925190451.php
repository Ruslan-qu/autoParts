<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250925190451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auto_parts_sold DROP CONSTRAINT FK_4C77DB17A07A8D1F');
        $this->addSql('ALTER TABLE auto_parts_sold ADD CONSTRAINT FK_4C77DB17A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE availability DROP CONSTRAINT FK_3FB7A2BFA07A8D1F');
        $this->addSql('ALTER TABLE availability ADD CONSTRAINT FK_3FB7A2BFA07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE axles DROP CONSTRAINT FK_5CB0ACB3A07A8D1F');
        $this->addSql('ALTER TABLE axles ADD CONSTRAINT FK_5CB0ACB3A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bodies DROP CONSTRAINT FK_CD58C0F1A07A8D1F');
        $this->addSql('ALTER TABLE bodies ADD CONSTRAINT FK_CD58C0F1A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car_brands DROP CONSTRAINT FK_66CAA8C6A07A8D1F');
        $this->addSql('ALTER TABLE car_brands ADD CONSTRAINT FK_66CAA8C6A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE counterparty DROP CONSTRAINT FK_9B3DE79CA07A8D1F');
        $this->addSql('ALTER TABLE counterparty ADD CONSTRAINT FK_9B3DE79CA07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE original_rooms DROP CONSTRAINT FK_B311FA64A07A8D1F');
        $this->addSql('ALTER TABLE original_rooms ADD CONSTRAINT FK_B311FA64A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE part_name DROP CONSTRAINT FK_248B008BA07A8D1F');
        $this->addSql('ALTER TABLE part_name ADD CONSTRAINT FK_248B008BA07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP CONSTRAINT FK_2675D15CA07A8D1F');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD CONSTRAINT FK_2675D15CA07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_method DROP CONSTRAINT FK_7B61A1F6A07A8D1F');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT FK_7B61A1F6A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE replacing_original_numbers DROP CONSTRAINT FK_5C3776F6A07A8D1F');
        $this->addSql('ALTER TABLE replacing_original_numbers ADD CONSTRAINT FK_5C3776F6A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sides DROP CONSTRAINT FK_A0260913A07A8D1F');
        $this->addSql('ALTER TABLE sides ADD CONSTRAINT FK_A0260913A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE auto_parts_sold DROP CONSTRAINT fk_4c77db17a07a8d1f');
        $this->addSql('ALTER TABLE auto_parts_sold ADD CONSTRAINT fk_4c77db17a07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sides DROP CONSTRAINT fk_a0260913a07a8d1f');
        $this->addSql('ALTER TABLE sides ADD CONSTRAINT fk_a0260913a07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE axles DROP CONSTRAINT fk_5cb0acb3a07a8d1f');
        $this->addSql('ALTER TABLE axles ADD CONSTRAINT fk_5cb0acb3a07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE original_rooms DROP CONSTRAINT fk_b311fa64a07a8d1f');
        $this->addSql('ALTER TABLE original_rooms ADD CONSTRAINT fk_b311fa64a07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE availability DROP CONSTRAINT fk_3fb7a2bfa07a8d1f');
        $this->addSql('ALTER TABLE availability ADD CONSTRAINT fk_3fb7a2bfa07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE counterparty DROP CONSTRAINT fk_9b3de79ca07a8d1f');
        $this->addSql('ALTER TABLE counterparty ADD CONSTRAINT fk_9b3de79ca07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers DROP CONSTRAINT fk_2675d15ca07a8d1f');
        $this->addSql('ALTER TABLE part_numbers_from_manufacturers ADD CONSTRAINT fk_2675d15ca07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_method DROP CONSTRAINT fk_7b61a1f6a07a8d1f');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT fk_7b61a1f6a07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car_brands DROP CONSTRAINT fk_66caa8c6a07a8d1f');
        $this->addSql('ALTER TABLE car_brands ADD CONSTRAINT fk_66caa8c6a07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE replacing_original_numbers DROP CONSTRAINT fk_5c3776f6a07a8d1f');
        $this->addSql('ALTER TABLE replacing_original_numbers ADD CONSTRAINT fk_5c3776f6a07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE part_name DROP CONSTRAINT fk_248b008ba07a8d1f');
        $this->addSql('ALTER TABLE part_name ADD CONSTRAINT fk_248b008ba07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bodies DROP CONSTRAINT fk_cd58c0f1a07a8d1f');
        $this->addSql('ALTER TABLE bodies ADD CONSTRAINT fk_cd58c0f1a07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
