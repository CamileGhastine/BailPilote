<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i=0; $i < 20 ; $i++) { 
            $address = (new Address())
                ->setNumber($faker->numberBetween(1,230))
                ->setCity($faker->city())
                ->setStreet($faker->streetName())
                ->setZipCode((int) $faker->postcode())
                ->setCountry($faker->country())
                ;
            $manager->persist($address);
            $this->addReference('address_' . $i, $address);
           
        }

        $manager->flush();
    }
}
