<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
                'phone'  => '060606006',
                'roles'     => ['ROLE_USER'],
                'password'  => 'Password123456$',
                'phone'     => '0600000001',    
            ],
            [
                'email'     => 'admin@test.com',
                'firstname' => 'Alice',
                'lastname'  => 'Martin',
                'phone'  => '0101011001',
                'roles'     => ['ROLE_ADMIN', 'ROLE_USER'],
                'password'  => 'Password123456$',
                'phone'     => '0600000002',
            ],
        ];

        foreach ($users as $i => $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setPhone($data['phone']);
            $user->setRoles($data['roles']);
            $user->setIsVerified(true);
            $user->setPhone($data['phone']);
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        $faker =  Factory::create();

        for ($i=2 ; $i<20 ; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setPhone($faker->phoneNumber());
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(true);
            $user->setPassword($this->hasher->hashPassword($user, 'Password123456$'));

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
        $manager->flush();
    }
}
