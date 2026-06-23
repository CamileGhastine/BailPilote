<?php

namespace App\Controller;

use App\Entity\Lease;
use App\Entity\Payment;
use App\Form\LeaseType;
use App\Repository\LeaseRepository;
use App\Repository\PaymentRepository;
use App\Repository\PropertyRepository;
use App\Service\InseeIrlService;
use App\Service\QuittanceGenerator;
use App\Util\FrenchDate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

final class LeaseController extends AbstractController
{
    public function __construct(
        private PropertyRepository $propertyRepository,
        private LeaseRepository $leaseRepository,
        private PaymentRepository $paymentRepository,
        private EntityManagerInterface $em,
        private InseeIrlService $inseeIrlService,
        private QuittanceGenerator $quittanceGenerator,
    ) {
    }

    #[Route('/owner/property/{propertyId}/lease/save', name: 'app_lease_save')]
    public function save(Request $request, int $propertyId): Response
    {
        $property = $this->propertyRepository->find($propertyId);

        if (!$property) {
            throw $this->createNotFoundException('Propriété introuvable.');
        }

        $leaseId = $request->query->get('id');

        $lease = $leaseId
            ? $this->leaseRepository->find($leaseId)
            : new Lease();

        if (!$lease) {
            throw $this->createNotFoundException('Bail introuvable.');
        }

        $irls = $this->inseeIrlService->getHistory();

        $form = $this->createForm(LeaseType::class, $lease, ['irls' => $irls]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            [$period, $value] = explode('|', $form->get('irlSelection')->getData());
            $lease->setIrlPeriod($period);
            $lease->setIrlValue((float) $value);

            if (!$leaseId) {
                $lease->setProperty($property);
                $this->em->persist($lease);
                $this->addFlash('success', 'Bail créé avec succès.');
            } else {
                $this->addFlash('success', 'Bail mis à jour avec succès.');
            }

            $this->em->flush();

            return $this->redirectToRoute('app_owner_show', ['id' => $property->getId()]);
        }

        return $this->render('lease/save.html.twig', [
            'form' => $form,
            'property' => $property,
            'isEdit' => $leaseId !== null,
            'irls' => $irls
        ]);
    }

    #[Route('/owner/lease/{leaseId}/payment/{period}/quittance', name: 'app_owner_payment_quittance', requirements: ['period' => '\d{4}-\d{2}'])]
    public function ownerDownloadQuittance(int $leaseId, string $period): Response
    {
        $lease = $this->getOwnedLease($leaseId);
        $payment = $this->findOrCreatePayment($lease, $this->parsePeriod($period));

        $path = $this->quittanceGenerator->generate($payment);
        $this->em->flush();

        return $this->fileDownloadResponse($path, $period);
    }

    #[Route('/owner/lease/{leaseId}/payment/mark-paid', name: 'app_owner_payment_mark_paid', methods: ['POST'])]
    public function ownerMarkPaid(int $leaseId, Request $request): Response
    {
        $lease = $this->getOwnedLease($leaseId);

        if (!$this->isCsrfTokenValid('mark_paid_' . $leaseId, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $period = $this->paymentRepository->findOldestUnpaidPeriod($lease);

        if ($period === null) {
            $this->addFlash('info', 'Tous les mois sont déjà payés.');

            return $this->redirectToRoute('app_owner_show', ['id' => $lease->getProperty()->getId()]);
        }

        $dueDate = $this->paymentRepository->dueDateFor($lease, $period);

        if ($dueDate > new \DateTimeImmutable('today')) {
            $this->addFlash('error', "Ce mois ne peut pas encore être marqué comme payé : l'échéance n'est pas atteinte.");

            return $this->redirectToRoute('app_owner_show', ['id' => $lease->getProperty()->getId()]);
        }

        $clickedPeriod = $request->request->get('clicked_period');
        if ($clickedPeriod) {
            $clickedMonth = $this->parsePeriod($clickedPeriod);
            if ($clickedMonth != $period) {
                $this->addFlash('warning', sprintf(
                    "Vous ne pouvez pas marquer le mois de %s comme payé : des mois antérieurs sont encore impayés.",
                    FrenchDate::monthYear($clickedMonth)
                ));
            }
        }

        $payment = $this->findOrCreatePayment($lease, $period);

        $payment->setStatus(Payment::STATUS_PAID);
        $payment->setPaidAt(new \DateTimeImmutable());
        $this->em->flush();

        $this->addFlash('success', sprintf('Le mois de %s a été marqué comme payé.', FrenchDate::monthYear($period)));

        return $this->redirectToRoute('app_owner_show', [
            'id' => $lease->getProperty()->getId(),
            'year' => (int) $period->format('Y'),
        ]);
    }

    private function getOwnedLease(int $leaseId): Lease
    {
        $lease = $this->leaseRepository->find($leaseId);

        if (!$lease) {
            throw $this->createNotFoundException('Bail introuvable.');
        }

        if ($lease->getProperty()->getOwner()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $lease;
    }

    private function findOrCreatePayment(Lease $lease, \DateTimeImmutable $period): Payment
    {
        $payment = $this->paymentRepository->findOneByLeaseAndPeriod($lease, $period);

        if ($payment) {
            return $payment;
        }

        $payment = (new Payment())
            ->setLease($lease)
            ->setPeriod($period)
            ->setAmount($lease->getRentingAmount() + ($lease->getChargesAmount() ?? 0))
            ->setStatus(Payment::STATUS_PENDING)
        ;

        $this->em->persist($payment);

        return $payment;
    }

    private function parsePeriod(string $period): \DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $period . '-01');

        if (!$date) {
            throw $this->createNotFoundException('Période invalide.');
        }

        return $this->paymentRepository->periodFor($date);
    }

    private function fileDownloadResponse(string $path, string $period): Response
    {
        $response = new BinaryFileResponse($path);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'quittance-' . $period . '.pdf');

        return $response;
    }
}
