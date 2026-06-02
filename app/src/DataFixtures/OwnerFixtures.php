<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Owner;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OwnerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
    for ($i=0; $i < 20; $i++) { 
        
        $address = $this->getReference('address_' . $i, Address::class);
        $user = $this->getReference('user_' . $i, User::class);
        
        $owner = (new Owner())
            ->setAddress($address)
            ->setUser($user)
        ;
        $manager->persist($owner);
        $this->addReference('owner_' . $i, $owner);
    }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AddressFixtures::class,
            UserFixtures::class,
        ];
    }
    }
