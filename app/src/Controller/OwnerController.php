<?php

namespace App\Controller;

use App\Entity\Owner;
use App\Entity\Tenant;
use App\Form\OwnerProfilType;
use App\Form\TenantType;
use App\Repository\OwnerRepository;
use App\Repository\PaymentRepository;
use App\Repository\PropertyRepository;
use App\Repository\TenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\TenantHandler;

class OwnerController extends AbstractController
{
    public function __construct(
        private PropertyRepository $propertyRepository,
        private TenantRepository $tenantRepository,
        private TenantHandler $tenantHandler,
        private PaymentRepository $paymentRepository,
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/owner', name: 'app_owner_index')]
    public function index(PropertyRepository $propertyRepo, OwnerRepository $ownerRepo): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $owner = $ownerRepo->findBy(['user' => $user]);

        if(!$owner) $this->addFlash('info', 'Créez votre profil bailleur avant de pouvoir profiter des fonctionnalités de BailPilote.');
        $properties = $propertyRepo->findBy(['owner' => $owner]);

        return $this->render('owner/index.html.twig', [
            'properties' => $properties,
            'owner' => $owner
        ]);
    }
    
    #[Route('/owner/show/{id}', name: 'app_owner_show')]
    public function show(int $id, PropertyRepository $propertyRepo, TenantRepository $tenantRepo, Request $request): Response
    {
        $property = $propertyRepo->findWithAddress($id);
        $currentUser = $this->getUser();
        $propertyUser = $property->getOwner()->getUser();

        if ($currentUser != $propertyUser) {
        return $this->redirectToRoute('app_owner_index');
        }

        $tenants = $tenantRepo->findWithUserAndLease($property);

        $lease = $property->getLease();
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

        return $this->render('owner/show.html.twig', [
            'property' => $property,
            'tenants' => $tenants,
            'paymentsSchedule' => $paymentsSchedule,
            'years' => $years,
            'selectedYear' => $selectedYear,
        ]);
    }

    #[Route('/owner/property/{propertyId}/tenant/save', name: 'app_owner_save_tenant')]
    public function saveTenant(
        Request $request,
        int $propertyId,
    ): Response {
        $tenantId = $request->query->get('id');
        
        $property = $this->propertyRepository->find($propertyId);

        $tenant = $tenantId
            ? $this->tenantRepository->findWithUser($tenantId)
            : new Tenant();

        $form = $this->createForm(TenantType::class, $tenant);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($tenantId) {
                $this->tenantHandler->updateTenantFromForm($form, $tenant);
                $this->addFlash('success', 'Locataire mis à jour avec succès.');
            } else {
                $this->tenantHandler->createTenantFromForm($form, $property);
                $this->addFlash('success', 'Locataire ajouté avec succès.');
            }

            return $this->redirectToRoute('app_owner_show', ['id' => $property->getId()]);
        }

        return $this->render('owner/save_tenant.html.twig', [
            'property' => $property,
            'form' => $form,
            'tenant' => $tenant,
            'isEdit' => $tenantId !== null,
        ]);
    }

    #[Route('/owner/profil', name: 'app_owner_profil')]
    public function createProfil(Request $request, OwnerRepository $ownerRepo): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($ownerRepo->findOneBy(['user' => $user])) {
            return $this->redirectToRoute('app_owner_index');
        }

        $form = $this->createForm(OwnerProfilType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setPhone($data['phone'] ?? null);

            $owner = new Owner();
            $owner->setUser($user);

            $addressData = $request->request->all()['owner_profil'] ?? [];
            $street = trim($addressData['address']['street'] ?? '');
            if ($street !== '') {
                $owner->setAddress($data['address']);
            }

            $this->em->persist($owner);
            $this->em->flush();

            $this->addFlash('success', 'Votre profil bailleur a été créé avec succès.');

            return $this->redirectToRoute('app_owner_index');
        }

        return $this->render('owner/create_profil.html.twig', [
            'form' => $form,
        ]);
    }

}