<?php

namespace App\DataFixtures;

use App\Entity\Lease;
use App\Entity\Payment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PaymentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $today = new \DateTimeImmutable();

        for ($i = 0; $i < 20; $i++) {
            $lease = $this->getReference('lease_' . $i, Lease::class);
            $amount = $lease->getRentingAmount() + ($lease->getChargesAmount() ?? 0);
            $dueDateThisMonth = $today->setDate((int) $today->format('Y'), (int) $today->format('m'), $lease->getDateOfPayment())->setTime(0, 0);

            // Deux mois déjà payés
            foreach ([2, 1] as $monthsAgo) {
                $period = $dueDateThisMonth->modify("-{$monthsAgo} month");
                $payment = (new Payment())
                    ->setLease($lease)
                    ->setPeriod($period)
                    ->setAmount($amount)
                    ->setStatus(Payment::STATUS_PAID)
                    ->setPaidAt($period->modify('+1 day'))
                ;
                $manager->persist($payment);
            }

            // Mois courant : pas encore payé
            $pending = (new Payment())
                ->setLease($lease)
                ->setPeriod($dueDateThisMonth)
                ->setAmount($amount)
                ->setStatus(Payment::STATUS_PENDING)
            ;
            $manager->persist($pending);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LeaseFixtures::class,
        ];
    }
}
