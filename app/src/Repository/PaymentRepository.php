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
     * Liste les mois dus pour un bail, du début du bail au mois courant (les plus récents en premier),
     * chaque échéance tombant le jour de paiement contractuel du bail (Lease::dateOfPayment),
     * associée à son Payment s'il a déjà été créé (payé ou quittance générée à l'avance).
     * Le bail se renouvelant automatiquement, l'échéancier n'est pas borné par sa durée initiale.
     *
     * @return array<int, array{period: \DateTimeImmutable, payment: ?Payment}>
     */
    public function buildSchedule(Lease $lease): array
    {
        $start = $this->dueDateFor($lease, $lease->getLeasedAt());
        $end = $this->dueDateFor($lease, new \DateTimeImmutable());

        $paymentsByPeriod = [];
        foreach ($this->findByLease($lease) as $payment) {
            $paymentsByPeriod[$payment->getPeriod()->format('Y-m-d')] = $payment;
        }

        $schedule = [];
        for ($period = $start; $period <= $end; $period = $this->dueDateFor($lease, $period->modify('+1 month'))) {
            $schedule[] = [
                'period' => $period,
                'payment' => $paymentsByPeriod[$period->format('Y-m-d')] ?? null,
            ];
        }

        return array_reverse($schedule);
    }

    /**
     * Date d'échéance contractuelle (jour Lease::dateOfPayment) pour le mois du DateTimeImmutable donné.
     */
    public function dueDateFor(Lease $lease, \DateTimeImmutable $month): \DateTimeImmutable
    {
        return $month->setDate((int) $month->format('Y'), (int) $month->format('m'), $lease->getDateOfPayment())->setTime(0, 0);
    }
}
