<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OwnerController extends AbstractController
{
    #[Route('/owner', name: 'app_owner_index')]
    public function index(): Response
    {
        return $this->render('owner/index.html.twig');
    }
    
    #[Route('/owner/show', name: 'app_owner_show')]
    public function show(): Response
    {
        return $this->render('owner/show.html.twig', [
            'controller_name' => 'OwnerController',
        ]);
    }
}
