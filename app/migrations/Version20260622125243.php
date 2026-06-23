<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260622125243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, period DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, paid_at DATETIME DEFAULT NULL, receipt_path VARCHAR(255) DEFAULT NULL, receipt_generated_at DATETIME DEFAULT NULL, lease_id INT NOT NULL, INDEX IDX_6D28840DD3CA542C (lease_id), UNIQUE INDEX lease_period_unique (lease_id, period), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DD3CA542C FOREIGN KEY (lease_id) REFERENCES lease (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DD3CA542C');
        $this->addSql('DROP TABLE payment');
    }
}
