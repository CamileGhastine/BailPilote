<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260528145557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lease ADD rentingAmount DOUBLE PRECISION NOT NULL, ADD securityDeposit DOUBLE PRECISION NOT NULL, DROP renting_amount, DROP security_deposit, CHANGE leased_at leasedAt DATETIME NOT NULL, CHANGE irl_date irlDate VARCHAR(255) NOT NULL, CHANGE date_of_payment dateOfPayment INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lease ADD renting_amount DOUBLE PRECISION NOT NULL, ADD security_deposit DOUBLE PRECISION NOT NULL, DROP rentingAmount, DROP securityDeposit, CHANGE leasedAt leased_at DATETIME NOT NULL, CHANGE irlDate irl_date VARCHAR(255) NOT NULL, CHANGE dateOfPayment date_of_payment INT NOT NULL');
    }
}
