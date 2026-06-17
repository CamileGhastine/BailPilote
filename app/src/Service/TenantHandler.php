<?php

namespace App\Service;

use App\Entity\Property;
use App\Entity\Tenant;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class TenantHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
    ) {}

    public function createTenantFromForm(FormInterface $form, Property $property): void
    {
        $token = bin2hex(random_bytes(32));

        $tenant = $form->getData();
        $user = $tenant->getUser();
        $user->setRoles(['ROLE_TENANT']);
        $user->setIsVerified(false);
        $user->setRegistrationToken($token);
        $user->setPassword("");

        $lease = $property->getLease();
        if ($lease) {
            $tenant->setLease($lease);
        }

        $this->em->persist($tenant);
        $this->em->flush();

        if ($form->get('sendEmail')->getData()) {
            $this->sendInvitationEmail($user, $token);
        }
    }
    
    public function updateTenantFromForm(FormInterface $form, Tenant $tenant): void
    {
            $this->em->flush();
    }

    private function sendInvitationEmail(User $user, string $token): void
        {
            $email = (new Email())
            ->from('noreply@bailpilote.fr')
            ->to($user->getEmail())
            ->subject('Accès à votre compte BailPilote') 
            ->html(sprintf(
                '<p>Bonjour %s,</p>
                <p>Vous avez été ajouté en tant que locataire.
                <p>Cliquez sur ce lien pour définir votre mot de passe et accéder à votre compte :</p>
                <p><a href="%s">Définir mon mot de passe</a></p>',
                $user->getFirstname(),
                'http://localhost:8080/set-password?token=' . $token
                ));

            $this->mailer->send($email);
        }
    }