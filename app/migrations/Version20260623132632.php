<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260623132632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Normalise payment.period au 1er du mois : identité stable du paiement, indépendante de lease.dateOfPayment (qui ne sert plus qu\'à calculer la date d\'échéance affichée).';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE payment SET period = DATE_FORMAT(period, '%Y-%m-01 00:00:00')");
    }

    public function down(Schema $schema): void
    {
        // Non réversible : le jour d'origine (dateOfPayment au moment du paiement) n'est pas conservé.
    }
}
