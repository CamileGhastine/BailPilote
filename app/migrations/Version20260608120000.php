<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260608120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'ecoNote et gesNote passent nullable (terrain et parking n\'ont pas de notes énergie/GES)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE property CHANGE ecoNote ecoNote VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE property CHANGE gesNote gesNote VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE property CHANGE ecoNote ecoNote VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE gesNote gesNote VARCHAR(255) NOT NULL');
    }
}
