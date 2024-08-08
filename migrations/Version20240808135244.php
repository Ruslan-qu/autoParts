<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808135244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD id_counterparty_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD id_details_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD id_manufacturer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD id_payment_method_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD CONSTRAINT FK_FDE9454332416E8D FOREIGN KEY (id_counterparty_id) REFERENCES counterparty (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD CONSTRAINT FK_FDE945431E9087F5 FOREIGN KEY (id_details_id) REFERENCES part_numbers_from_manufacturers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD CONSTRAINT FK_FDE94543E37D77A5 FOREIGN KEY (id_manufacturer_id) REFERENCES part_numbers_from_manufacturers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD CONSTRAINT FK_FDE94543AB967E58 FOREIGN KEY (id_payment_method_id) REFERENCES payment_method (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FDE9454332416E8D ON auto_parts_warehouse (id_counterparty_id)');
        $this->addSql('CREATE INDEX IDX_FDE945431E9087F5 ON auto_parts_warehouse (id_details_id)');
        $this->addSql('CREATE INDEX IDX_FDE94543E37D77A5 ON auto_parts_warehouse (id_manufacturer_id)');
        $this->addSql('CREATE INDEX IDX_FDE94543AB967E58 ON auto_parts_warehouse (id_payment_method_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP CONSTRAINT FK_FDE9454332416E8D');
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP CONSTRAINT FK_FDE945431E9087F5');
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP CONSTRAINT FK_FDE94543E37D77A5');
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP CONSTRAINT FK_FDE94543AB967E58');
        $this->addSql('DROP INDEX IDX_FDE9454332416E8D');
        $this->addSql('DROP INDEX IDX_FDE945431E9087F5');
        $this->addSql('DROP INDEX IDX_FDE94543E37D77A5');
        $this->addSql('DROP INDEX IDX_FDE94543AB967E58');
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP id_counterparty_id');
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP id_details_id');
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP id_manufacturer_id');
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP id_payment_method_id');
    }
}
