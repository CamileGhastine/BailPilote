<?php

namespace App\Repository;

use App\Entity\Lease;
use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function findOneByLeaseAndPeriod(Lease $lease, \DateTimeImmutable $period): ?Payment
    {
        return $this->createQueryBuilder('p')
            ->where('p.lease = :lease')
            ->andWhere('p.period = :period')
            ->setParameter('lease', $lease)
            ->setParameter('period', $period)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Payment[]
     */
    public function findByLease(Lease $lease): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.lease = :lease')
            ->setParameter('lease', $lease)
            ->orderBy('p.period', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Liste les mois dus pour un bail, du début du bail au mois courant (les plus récents en premier).
     * `period` identifie le mois civil (1er du mois) : c'est une clé stable, indépendante de
     * Lease::dateOfPayment, qui peut changer au fil du temps sans désynchroniser les paiements
     * déjà enregistrés. `dueDate` est l'échéance affichée (jour Lease::dateOfPayment courant).
     * Le bail se renouvelant automatiquement, l'échéancier n'est pas borné par sa durée initiale.
     *
     * @return array<int, array{period: \DateTimeImmutable, dueDate: \DateTimeImmutable, payment: ?Payment}>
     */
    public function buildSchedule(Lease $lease): array
    {
        $start = $this->periodFor($lease->getLeasedAt());
        $end = $this->periodFor(new \DateTimeImmutable());

        $paymentsByPeriod = [];
        foreach ($this->findByLease($lease) as $payment) {
            $paymentsByPeriod[$payment->getPeriod()->format('Y-m')] = $payment;
        }

        $schedule = [];
        for ($month = $start; $month <= $end; $month = $month->modify('+1 month')) {
            $schedule[] = [
                'period' => $month,
                'dueDate' => $this->dueDateFor($lease, $month),
                'payment' => $paymentsByPeriod[$month->format('Y-m')] ?? null,
            ];
        }

        return array_reverse($schedule);
    }

    /**
     * Mois civil (identité stable, indépendante de dateOfPayment) du plus ancien mois impayé
     * du bail (le locataire paie ses mois dans l'ordre). Retourne null si tout est déjà payé.
     */
    public function findOldestUnpaidPeriod(Lease $lease): ?\DateTimeImmutable
    {
        $schedule = $this->buildSchedule($lease);

        for ($i = count($schedule) - 1; $i >= 0; $i--) {
            $payment = $schedule[$i]['payment'];

            if (!$payment || !$payment->isPaid()) {
                return $schedule[$i]['period'];
            }
        }

        return null;
    }

    /**
     * Identité stable d'un mois civil (1er du mois, sans heure) : c'est la clé utilisée pour
     * retrouver/persister un Payment, indépendamment de la valeur courante de dateOfPayment.
     */
    public function periodFor(\DateTimeImmutable $date): \DateTimeImmutable
    {
        return $date->setDate((int) $date->format('Y'), (int) $date->format('m'), 1)->setTime(0, 0);
    }

    /**
     * Date d'échéance contractuelle (jour Lease::dateOfPayment courant) pour le mois civil donné.
     * Usage : affichage et contrainte "le mois n'est pas encore dû" — jamais pour identifier un Payment.
     */
    public function dueDateFor(Lease $lease, \DateTimeImmutable $month): \DateTimeImmutable
    {
        return $month->setDate((int) $month->format('Y'), (int) $month->format('m'), $lease->getDateOfPayment())->setTime(0, 0);
    }
}
