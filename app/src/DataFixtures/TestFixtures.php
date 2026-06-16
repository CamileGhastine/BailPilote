<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Lease;
use App\Entity\Owner;
use App\Entity\Property;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture
{   
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // User
        $user = new User();
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setEmail('john.doe@example.com');
        $user->setPhone('0123456789');
        $user->setRoles(['ROLE_OWNER']);
        $user->setIsVerified(true);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $manager->persist($user);
        $manager->flush();

        // Address Owner
        $ownerAddress = new Address();
        $ownerAddress->setNumber(5);
        $ownerAddress->setStreet('Avenue des Champs-Élysées');
        $ownerAddress->setZipCode(75008);
        $ownerAddress->setCity('Paris');
        $ownerAddress->setCountry('France');
        $manager->persist($ownerAddress);
        $manager->flush();

        // Owner
        $owner = new Owner();
        $owner->setUser($user);
        $owner->setAddress($ownerAddress);
        $manager->persist($owner);
        $manager->flush();

        // Address Property
        $propertyAddress = new Address();
        $propertyAddress->setNumber(12);
        $propertyAddress->setStreet('Rue de la Paix');
        $propertyAddress->setZipCode(75002);
        $propertyAddress->setCity('Paris');
        $propertyAddress->setCountry('France');
        $manager->persist($propertyAddress);
        $manager->flush();

        // Property
        $property = new Property();
        $property->setTitle('Appartement T2');
        $property->setType('Appartement');
        $property->setArea(50);
        $property->setNumberOfRooms(3);
        $property->setNumberOfBedrooms(2);
        $property->setEcoNote('B');
        $property->setGesNote('C');
        $property->setDescription('Un bel appartement situé au cœur de Paris.');
        $property->setCriteria(['Proche des transports, Lumineux, Calme']);
        $property->setStatus('disponible');
        $property->setOwner($owner);
        $property->setAddress($propertyAddress);
        $manager->persist($property);
        $manager->flush();

        // Lease
        $lease = new Lease();
        $lease->setType('location');
        $lease->setRentingAmount(800);
        $lease->setChargesAmount((int) (800 / 5));
        $lease->setLeasedAt(new \DateTimeImmutable());
        $lease->setDuration(12);
        $lease->setIrlDate(new \DateTimeImmutable('2024-01-30'));
        $lease->setIrl(1.0);
        $lease->setSecurityDeposit(1600);
        $lease->setDateOfPayment(5);
        $lease->setProperty($property);
        $manager->persist($lease);
        $manager->flush();
    }
}