<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TemplateController extends AbstractController
{
    #[Route('/', name: 'app_template_index')]
    public function index(): Response
    {
        return $this->render('template/index.html.twig', [
          
        ]);
    }
}
