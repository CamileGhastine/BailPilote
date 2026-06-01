<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\OwnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PropertyController extends AbstractController
{
    #[Route('/property/new', name: 'app_property_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        OwnerRepository $ownerRepository,
    ): Response {
        $property = new Property();

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $owner = $ownerRepository->findOneBy(['user' => $this->getUser()]);

            if (!$owner) {
                $this->addFlash('danger', 'Aucun profil propriétaire trouvé pour votre compte.');
                return $this->redirectToRoute('app_property_new');
            }

            $property->setOwner($owner);

            $entityManager->persist($property);
            $entityManager->flush();

            $this->addFlash('success', 'Le bien immobilier a bien été créé.');

            return $this->redirectToRoute('app_property_new');
        }

        return $this->render('property/new.html.twig', [
            'form' => $form,
        ]);
    }
}