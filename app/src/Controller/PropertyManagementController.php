<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PropertyManagementController extends AbstractController
{
    #[Route('/property/management', name: 'app_property_management')]
    public function index(): Response
    {
        return $this->render('property_management/index.html.twig', [
            'controller_name' => 'PropertyManagementController',
        ]);
    }
}
