<?php

namespace App\Controller;

use App\Entity\Lease;
use App\Form\LeaseType;
use App\Repository\LeaseRepository;
use App\Repository\PropertyRepository;
use App\Service\InseeIrlService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LeaseController extends AbstractController
{
    public function __construct(
        private PropertyRepository $propertyRepository,
        private LeaseRepository $leaseRepository,
        private EntityManagerInterface $em,
        private InseeIrlService $inseeIrlService,
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
}
