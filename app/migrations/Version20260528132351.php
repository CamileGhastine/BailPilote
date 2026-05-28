<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260528132351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address CHANGE zip_code zipCode INT NOT NULL, CHANGE address_info addressInfo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD numberOfRooms INT NOT NULL, ADD numberOfBedrooms INT NOT NULL, ADD ecoNote VARCHAR(255) NOT NULL, ADD gesNote VARCHAR(255) NOT NULL, DROP number_of_rooms, DROP number_of_bedrooms, DROP eco_note, DROP ges_note');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address CHANGE zipCode zip_code INT NOT NULL, CHANGE addressInfo address_info VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD number_of_rooms INT NOT NULL, ADD number_of_bedrooms INT NOT NULL, ADD eco_note VARCHAR(255) NOT NULL, ADD ges_note VARCHAR(255) NOT NULL, DROP numberOfRooms, DROP numberOfBedrooms, DROP ecoNote, DROP gesNote');
    }
}
