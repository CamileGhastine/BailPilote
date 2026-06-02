<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Owner;
use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Override;

class PropertyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $types = ['Appartement','Maison', 'Villa', 'Studio', 'Duplex', 'Bureau', 'Garage', 'Triplex'];
        $quality = ['moderne', 'lumineux', 'rénové', 'spacieux', 'élégant'];
        $extras = ['avec balcon', 'avec jardin', 'proche du centre', 'avec piscine'];
        $criteria = ['Vue mer','Fibre optique', 'Cave','Ascenseur','Proche écoles',   'Quartier calme','Piscine',   'Jardin', 'Garage', 'Terrasse',];
        $statuses = ['Disponible','Vendu', 'Loué','Sous offre','En attente',];
        $ecoNotes = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        $gesNotes = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

        for ($i=0; $i < 40 ; $i++) {
            $address = $this->getReference('address_' . $i, Address::class);
            $owner = $this->getReference('owner_' . rand(0, 19), Owner::class);

            $property = (new Property())->setType($faker->randomElement($types));
            $property->setTitle($property->getType() .' '. $faker->randomElement($quality) .' '. $faker->randomElement($extras))
                ->setArea($faker->numberBetween(20, 100))
                ->setDescription($faker->paragraph())
                ->setCriteria($faker->randomElement($criteria))
                ->setStatus($faker->randomElement($statuses))
                ->setNumberOfRooms($faker->numberBetween(1, 5))
                ->setNumberOfBedrooms($faker->numberBetween(1, 2))
                ->setEcoNote($faker->randomElement($ecoNotes))
                ->setGesNote($faker->randomElement($gesNotes))
                ->setOwner($owner)
                ->setAddress($address)
                ;

            $manager->persist($property);
            $this->addReference('property_' . $i, $property);            
        }

         $manager->flush();
    }

    #[Override]
    public function getDependencies(): array
    {
        return [
            AddressFixtures::class,
            OwnerFixtures::class,
        ];
    }
}
