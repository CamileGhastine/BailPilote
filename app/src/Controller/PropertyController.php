<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\OwnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class PropertyController extends AbstractController
{
    #[Route('/property/new/{id?}', name: 'app_property_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        ?Property $property,
        Request $request,
        EntityManagerInterface $entityManager,
        OwnerRepository $ownerRepository,
        SluggerInterface $slugger,
        #[Autowire('%property_images_directory%')] string $imagesDirectory,
        #[Autowire('%property_images_web_path%')] string $imagesWebPath,
    ): Response {
        if (!$property) $property = new Property;

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $owner = $ownerRepository->findOneBy(['user' => $this->getUser()]);

            if (!$owner) {
                $this->addFlash('danger', 'Aucun profil propriétaire trouvé pour votre compte.');
                return $this->redirectToRoute('app_home');
            }

            $property->setOwner($owner);

            $uploadedImages = [];

            foreach ($form->get('images') as $imageForm) {
                /** @var Image $image */
                $image = $imageForm->getData();
                $file = $imageForm->get('file')->getData();

                if (!$file) {
                    $property->removeImage($image);
                    continue;
                }

                $mimeType = $file->getMimeType() ?? 'image/jpeg';
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeName = $slugger->slug($originalName);
                $newFilename = $safeName . '-' . uniqid() . '.' . $file->guessExtension();

                $file->move($imagesDirectory, $newFilename);

                $image->setPath($imagesWebPath . '/' . $newFilename);
                $image->setTitle($originalName);
                $image->setType($mimeType);

                $uploadedImages[] = $image;
            }

            $hasPrincipal = !empty(array_filter($uploadedImages, fn($img) => $img->getIsPrincipal()));
            if (!empty($uploadedImages) && !$hasPrincipal) {
                $uploadedImages[0]->setIsPrincipal(true);
            }

            $entityManager->persist($property);
            $entityManager->flush();

            $this->addFlash('success', 'Le bien immobilier a bien été créé.');

            return $this->redirectToRoute('app_owner_show', ['id' => $property->getId()]);
        }

        return $this->render('property/new.html.twig', [
            'form' => $form,
        ]);
    }
}
