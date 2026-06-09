<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260602140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'numberOfRooms et numberOfBedrooms passent nullable (terrain et parking n\'ont pas de pièces)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE property CHANGE numberOfRooms numberOfRooms INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property CHANGE numberOfBedrooms numberOfBedrooms INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE property CHANGE numberOfRooms numberOfRooms INT NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE numberOfBedrooms numberOfBedrooms INT NOT NULL');
    }
}
