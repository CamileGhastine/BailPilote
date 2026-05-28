<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'email'     => 'bailleur@test.com',
                'firstname' => 'Jean',
                'lastname'  => 'Dupont',
                'roles'     => ['ROLE_USER'],
                'password'  => 'Password123456$',
            ],
            [
                'email'     => 'admin@test.com',
                'firstname' => 'Alice',
                'lastname'  => 'Martin',
                'roles'     => ['ROLE_ADMIN', 'ROLE_USER'],
                'password'  => 'Password123456$',
            ],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setRoles($data['roles']);
            $user->setIsVerified(true);
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
