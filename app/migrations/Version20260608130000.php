<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260608130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'le numéro de l\'adresse devient une chaîne pour autoriser des valeurs comme "4Bis"';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE address CHANGE number number VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE address CHANGE number number INT NOT NULL');
    }
}
