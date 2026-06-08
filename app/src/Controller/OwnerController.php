<?php

namespace App\Controller;
use App\Entity\Property;
use App\Entity\Tenant;
use App\Form\TenantType;
use App\Repository\OwnerRepository;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\TenantHandler;

class OwnerController extends AbstractController
{
    #[Route('/owner', name: 'app_owner_index')]
    public function index(PropertyRepository $propertyRepo, OwnerRepository $ownerRepo): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $owner = $ownerRepo->findBy(['user' => $user]);
        $properties = $propertyRepo->findBy(['owner' => $owner]);

        return $this->render('owner/index.html.twig', [
            'properties' => $properties,
        ]);
    }
    
    #[Route('/owner/show/{id}', name: 'app_owner_show')]
    public function show(int $id, PropertyRepository $propertyRepo): Response
    {
        $property = $propertyRepo->findWithAddress($id);
        
        return $this->render('owner/show.html.twig', [
            'property' => $property,
        ]);
    }
    
    #[Route('/owner/property/{id}/add-tenant', name: 'app_owner_add_tenant')]
    public function addTenant(
        Property $property,
        Request $request,
        TenantHandler $tenantHandler,
        ): Response
    {   
        $lease = $property->getLease();

        if (!$lease) {
            $this->addFlash('danger', 'Aucun bail associé à cette propriété.');
            return $this->redirectToRoute('app_owner_show', ['id' => $property->getId()]);
        }

        $tenant = new Tenant();
        $form = $this->createForm(TenantType::class, $tenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tenantHandler->createTenantFromForm($form, $lease);
            
            $this->addFlash('success', 'Locataire ajouté avec succès.');
            return $this->redirectToRoute('app_owner_show', ['id' => $property->getId()]);
        }

        return $this->render('owner/add_tenant.html.twig', [
            'property' => $property,
            'form' => $form
        ]);
    }
}
