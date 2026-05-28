<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/account')]
#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    #[Route('/change-password', name: 'account_change_password', methods: ['GET', 'POST'])]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentPassword = $form->get('currentPassword')->getData();

            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error', "L'ancien mot de passe est incorrect.");

                return $this->render('account/change_password.html.twig', [
                    'form' => $form,
                ]);
            }

            $newPassword = $form->get('newPassword')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $userRepository->upgradePassword($user, $hashedPassword);

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');

            return $this->redirectToRoute('account_change_password');
        }

        return $this->render('account/change_password.html.twig', [
            'form' => $form,
        ]);
    }
}
