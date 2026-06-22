<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260622082103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address CHANGE number number VARCHAR(10) NOT NULL, CHANGE zipCode zipCode VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE image CHANGE alt alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE owner CHANGE address_id address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property CHANGE criteria criteria JSON NOT NULL, CHANGE numberOfRooms numberOfRooms INT DEFAULT NULL, CHANGE numberOfBedrooms numberOfBedrooms INT DEFAULT NULL, CHANGE ecoNote ecoNote VARCHAR(255) DEFAULT NULL, CHANGE gesNote gesNote VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address CHANGE number number INT NOT NULL, CHANGE zipCode zipCode INT NOT NULL');
        $this->addSql('ALTER TABLE image CHANGE alt alt VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE owner CHANGE address_id address_id INT NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE numberOfRooms numberOfRooms INT NOT NULL, CHANGE numberOfBedrooms numberOfBedrooms INT NOT NULL, CHANGE ecoNote ecoNote VARCHAR(255) NOT NULL, CHANGE gesNote gesNote VARCHAR(255) NOT NULL, CHANGE criteria criteria LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE firstname firstname VARCHAR(255) NOT NULL, CHANGE lastname lastname VARCHAR(255) NOT NULL, CHANGE phone phone VARCHAR(255) NOT NULL');
    }
}
