<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260528122628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE owner ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE owner ADD CONSTRAINT FK_CF60E67CF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CF60E67CF5B7AF75 ON owner (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE owner DROP FOREIGN KEY FK_CF60E67CF5B7AF75');
        $this->addSql('DROP INDEX UNIQ_CF60E67CF5B7AF75 ON owner');
        $this->addSql('ALTER TABLE owner DROP address_id');
    }
}
