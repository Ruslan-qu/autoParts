<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250925184840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP CONSTRAINT FK_FDE94543A07A8D1F');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD CONSTRAINT FK_FDE94543A07A8D1F FOREIGN KEY (id_participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE auto_parts_warehouse DROP CONSTRAINT fk_fde94543a07a8d1f');
        $this->addSql('ALTER TABLE auto_parts_warehouse ADD CONSTRAINT fk_fde94543a07a8d1f FOREIGN KEY (id_participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
