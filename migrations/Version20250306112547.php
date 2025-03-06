<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306112547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auto_parts_warehouse ALTER date_receipt_auto_parts_warehouse TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN auto_parts_warehouse.date_receipt_auto_parts_warehouse IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE auto_parts_warehouse ALTER date_receipt_auto_parts_warehouse TYPE DATE');
        $this->addSql('COMMENT ON COLUMN auto_parts_warehouse.date_receipt_auto_parts_warehouse IS \'(DC2Type:date_immutable)\'');
    }
}
