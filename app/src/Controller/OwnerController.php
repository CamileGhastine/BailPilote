<?php

namespace App\Controller;

use App\Entity\Tenant;
use App\Form\TenantType;
use App\Repository\OwnerRepository;
use App\Repository\PropertyRepository;
use App\Repository\TenantRepository;
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
        $properties = $propertyRepo->findBy(['owner' => $owner]);
        ($properties);
        return $this->render('owner/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    #[Route('/owner/show', name: 'app_owner_show')]
    public function show(Request $request): Response
    {
        $id = $request->query->get('id');
        $property = $this->propertyRepository->findWithLeaseAndTenants($id);

        return $this->render('owner/show.html.twig', [
            'property' => $property,
        ]);
    }

    #[Route('/owner/property/{propertyId}/tenant/save', name: 'app_owner_save_tenant')]
    public function saveTenant(
        Request $request,
        int $propertyId,
    ): Response {
        $tenantId = $request->query->get('id');
        
        $property = $this->propertyRepository->find($propertyId);
        $lease = $property->getLease();

        if (!$lease) {
            $this->addFlash('danger', 'Aucun bail associé à cette propriété.');
            return $this->redirectToRoute('app_owner_show', ['id' => $property->getId()]);
        }

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
                $this->tenantHandler->createTenantFromForm($form, $lease);
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
}