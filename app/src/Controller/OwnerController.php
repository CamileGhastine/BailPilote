<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OwnerController extends AbstractController
{
    #[Route('/owner/show', name: 'app_owner_show')]
    public function index(): Response
    {
        return $this->render('owner/show.html.twig', [
            'controller_name' => 'OwnerController',
        ]);
    }
}
