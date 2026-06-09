<?php

namespace App\DataFixtures;

use App\Entity\Lease;
use App\Entity\Tenant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TenantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
    for ($i=0; $i < 20; $i++) { 
        
        $lease = $this->getReference('lease_' . $i, Lease::class);
        $user = $this->getReference('user_' . $i+20, User::class);
        
        $tenant = (new Tenant())
            ->setLease($lease)
            ->setUser($user)
        ;
        $manager->persist($tenant);
        $this->addReference('tenant_' . $i, $tenant);
    }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LeaseFixtures::class,
            UserFixtures::class,
        ];
    }
    
}
