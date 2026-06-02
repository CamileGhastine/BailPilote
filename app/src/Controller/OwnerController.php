<?php

namespace App\Controller;

use App\Repository\OwnerRepository;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
        ($properties);
        return $this->render('owner/index.html.twig', [
            'properties' => $properties,
        ]);
    }
    
    #[Route('/owner/show', name: 'app_owner_show')]
    public function show(): Response
    {
        return $this->render('owner/show.html.twig', [
            'controller_name' => 'OwnerController',
        ]);
    }
}
