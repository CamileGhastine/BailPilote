<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\OwnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $manager, OwnerRepository $ownerRepo): Response
    {  
        return $this->render('home/index.html.twig');
    }
}
