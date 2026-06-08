<?php

namespace App\DataFixtures;

use App\Entity\Guarantor;
use App\Entity\Lease;
use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LeaseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $types = ['Location vide', 'Location meublée', 'Location étudiante', 'Location saisonnière', 'Bail mobilité', 'Bail commercial', 'Bail professionnel'];


        $faker = Factory::create();

        for ($i=0; $i < 20 ; $i++) { 
            $property = $this->getReference('property_'. $i*2, Property::class);
            
            $leaseAt = new \DateTimeImmutable($faker->dateTimeBetween('-4 years', 'now')->format('d-m-Y'));            
            $irlDate = new \DateTimeImmutable ($faker->dateTimeBetween('-3 years', 'now')->format('d-m-Y'));            
            
            $lease = (new Lease())
                ->setType($faker->randomElement($types))
                ->setLeasedAt($leaseAt)
                ->setDuration($faker->numberBetween(3, 24))
                ->setIrlDate($irlDate)
                ->setIrl($faker->randomFloat(2, 125, 160))
            ;
            $lease
                ->setDateOfPayment($faker->numberBetween(3, 14))
                ->setRentingAmount($faker->numberBetween(800, 2500))
            ;
            $lease
                ->setSecurityDeposit($lease->getRentingAmount())
                ->setChargesAmount((int) ($lease->getRentingAmount()/5))
                ->setProperty($property)
            ;

            $this->addReference('lease_' . $i, $lease);
            $manager->persist($lease);
        }



        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PropertyFixtures::class,
        ];
    }

}
