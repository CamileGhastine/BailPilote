<?php

namespace App\DataFixtures;

use App\Entity\Guarantor;
use App\Entity\Lease;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GuarantorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $faker =  Factory::create();

        for ($i=2 ; $i<20 ; $i++) {
            $lease = $this->getReference('lease_'. $i, Lease::class);

            $guarantor = (new Guarantor())
                ->setEmail($faker->email())
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPhone($faker->phoneNumber())
                ->setLease($lease)
                ;
            $manager->persist($guarantor);
            $this->addReference('guarantor_' . $i, $guarantor);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LeaseFixtures::class,
        ];
    }
}
