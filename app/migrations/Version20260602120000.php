<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260602120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'zipCode INT -> VARCHAR(10) pour conserver les zéros initiaux ; alt de image passe nullable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE address CHANGE zipCode zipCode VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE image CHANGE alt alt VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE address CHANGE zipCode zipCode INT NOT NULL');
        $this->addSql('ALTER TABLE image CHANGE alt alt VARCHAR(255) NOT NULL');
    }
}
