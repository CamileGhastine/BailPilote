<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260601130617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP is_main_image');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY `FK_8BF21CDAA76ED395`');
        $this->addSql('DROP INDEX IDX_8BF21CDAA76ED395 ON property');
        $this->addSql('ALTER TABLE property DROP user_id, CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD phone VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD is_main_image TINYINT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD user_id INT NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT `FK_8BF21CDAA76ED395` FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8BF21CDAA76ED395 ON property (user_id)');
        $this->addSql('ALTER TABLE user DROP phone');
    }
}
