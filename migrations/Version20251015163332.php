<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251015163332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE sender_emails_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sender_emails (id INT NOT NULL, id_participant_id INT DEFAULT NULL, email_smtp VARCHAR(255) NOT NULL, password_smtp VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B24AD64DA07A8D1F ON sender_emails (id_participant_id)');
        $this->addSql('ALTER TABLE sender_emails ADD CONSTRAINT FK_B24AD64DA07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE sender_emails_id_seq CASCADE');
        $this->addSql('ALTER TABLE sender_emails DROP CONSTRAINT FK_B24AD64DA07A8D1F');
        $this->addSql('DROP TABLE sender_emails');
    }
}
