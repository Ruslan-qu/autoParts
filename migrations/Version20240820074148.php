<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240820074148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE counterparty ALTER manager_phone TYPE VARCHAR(13)');
        $this->addSql('ALTER TABLE counterparty ALTER delivery_phone TYPE VARCHAR(13)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE counterparty ALTER manager_phone TYPE INT');
        $this->addSql('ALTER TABLE counterparty ALTER manager_phone TYPE INT');
        $this->addSql('ALTER TABLE counterparty ALTER delivery_phone TYPE INT');
        $this->addSql('ALTER TABLE counterparty ALTER delivery_phone TYPE INT');
    }
}
