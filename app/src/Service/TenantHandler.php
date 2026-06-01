<?php
use App\Entity\Lease;
use App\Entity\Tenant;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TenantHandler
{
    public function __construct(
      private EntityManagerInterface $em,
      private UserPasswordHasherInterface $passwordHasher
    )
    {}

    public function createTenantForm(FormInterface $form, Lease $lease): void
    {}
    
        $user = new User();
        $user->setFirstName($form->get('firstName')->getData());
        $user->setLastName($form->get('lastName')->getData());
        $user->setEmail($form->get('email')->getData());
        $user->setPhone($form->get('phone')->getData());
        $user->setRoles(['ROLE_TENANT']);
        $user->setIsVerified(false);
        
        $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
        $user->setPassword($hashedPassword);

        $tenant = new Tenant();
        $tenant->setUser($user);
        $tenant->setLease($lease);

        $this->em->persist($user);
        $this->em->persist($tenant);
        $this->em->flush();
    }