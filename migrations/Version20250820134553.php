<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250820134553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE counterparty ADD id_participant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE counterparty ADD CONSTRAINT FK_9B3DE79CA07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9B3DE79CA07A8D1F ON counterparty (id_participant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE counterparty DROP CONSTRAINT FK_9B3DE79CA07A8D1F');
        $this->addSql('DROP INDEX IDX_9B3DE79CA07A8D1F');
        $this->addSql('ALTER TABLE counterparty DROP id_participant_id');
    }
}
