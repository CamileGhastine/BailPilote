<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Owner;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OwnerFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->userRepository->findOneBy([
            'email' => 'bailleur@test.com',
        ]);

        if (!$user) {
            throw new \Exception('Utilisateur bailleur@test.com introuvable.');
        }

        $address = new Address();
        $address->setNumber(12);
        $address->setStreet('Rue de la Paix');
        $address->setZipCode(75001);
        $address->setCity('Paris');

        $owner = new Owner();
        $owner->setUser($user);
        $owner->setAddress($address);

        $manager->persist($address);
        $manager->persist($owner);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}