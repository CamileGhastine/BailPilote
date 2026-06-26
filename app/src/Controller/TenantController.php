<?php

namespace App\Controller;

use App\Repository\PaymentRepository;
use App\Repository\TenantRepository;
use App\Service\QuittanceGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_TENANT')]
class TenantController extends AbstractController
{
    public function __construct(
        private TenantRepository $tenantRepository,
        private PaymentRepository $paymentRepository,
        private QuittanceGenerator $quittanceGenerator,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/tenant', name: 'app_tenant_index')]
    public function index(Request $request): Response
    {
        $tenant = $this->tenantRepository->findOneBy(['user' => $this->getUser()]);
        $lease = $tenant?->getLease();

        $currentYear = (int) (new \DateTimeImmutable())->format('Y');
        $years = [];
        $selectedYear = $currentYear;
        $paymentsSchedule = [];

        if ($lease) {
            $startYear = (int) $lease->getLeasedAt()->format('Y');
            for ($year = $currentYear; $year >= $startYear; $year--) {
                $years[] = $year;
            }

            $requestedYear = $request->query->getInt('year', $currentYear);
            $selectedYear = in_array($requestedYear, $years, true) ? $requestedYear : $currentYear;

            $paymentsSchedule = array_values(array_filter(
                $this->paymentRepository->buildSchedule($lease),
                fn (array $entry) => (int) $entry['period']->format('Y') === $selectedYear
            ));
        }

        return $this->render('tenant/index.html.twig', [
            'lease' => $lease,
            'property' => $lease?->getProperty(),
            'paymentsSchedule' => $paymentsSchedule,
            'years' => $years,
            'selectedYear' => $selectedYear,
        ]);
    }

    #[Route('/tenant/payment/{period}/quittance', name: 'app_tenant_payment_quittance', requirements: ['period' => '\d{4}-\d{2}'])]
    public function downloadQuittance(string $period): Response
    {
        $tenant = $this->tenantRepository->findOneBy(['user' => $this->getUser()]);
        $lease = $tenant?->getLease();

        if (!$lease) {
            throw $this->createAccessDeniedException();
        }

        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $period . '-01');

        if (!$date) {
            throw $this->createNotFoundException('Période invalide.');
        }

        $payment = $this->paymentRepository->findOneByLeaseAndPeriod($lease, $this->paymentRepository->periodFor($date));

        if (!$payment || !$payment->isPaid()) {
            throw $this->createAccessDeniedException("Ce mois n'est pas encore marqué comme payé.");
        }

        $path = $this->quittanceGenerator->generate($payment);
        $this->em->flush();

        $response = new BinaryFileResponse($path);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'quittance-' . $period . '.pdf');

        return $response;
    }
}
