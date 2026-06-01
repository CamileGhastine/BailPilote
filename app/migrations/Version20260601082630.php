<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260601082630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lease DROP FOREIGN KEY `FK_E6C774959033212A`');
        $this->addSql('DROP INDEX UNIQ_E6C774959033212A ON lease');
        $this->addSql('ALTER TABLE lease DROP tenant_id');
        $this->addSql('ALTER TABLE tenant ADD lease_id INT NOT NULL');
        $this->addSql('ALTER TABLE tenant ADD CONSTRAINT FK_4E59C462D3CA542C FOREIGN KEY (lease_id) REFERENCES lease (id)');
        $this->addSql('CREATE INDEX IDX_4E59C462D3CA542C ON tenant (lease_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lease ADD tenant_id INT NOT NULL');
        $this->addSql('ALTER TABLE lease ADD CONSTRAINT `FK_E6C774959033212A` FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6C774959033212A ON lease (tenant_id)');
        $this->addSql('ALTER TABLE tenant DROP FOREIGN KEY FK_4E59C462D3CA542C');
        $this->addSql('DROP INDEX IDX_4E59C462D3CA542C ON tenant');
        $this->addSql('ALTER TABLE tenant DROP lease_id');
    }
}
