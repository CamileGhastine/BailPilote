<?php

namespace App\Controller;
use App\Entity\Property;
use App\Entity\Tenant;
use App\Form\TenantType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\TenantHandler;

class OwnerController extends AbstractController
{
    #[Route('/owner', name: 'app_owner_index')]
    public function index(): Response
    {
        return $this->render('owner/index.html.twig');
    }
    
    #[Route('/owner/show', name: 'app_owner_show')]
    public function show(Request $request, PropertyRepository $propertyRepository): Response
    {
    $id = $request->query->get('id');
    $property = $propertyRepository->find($id);

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
