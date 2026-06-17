<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\Tenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tenant>
 */
class TenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tenant::class);
    }


    public function findWithUserAndLease(Property $property): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.user', 'u')
            ->addSelect('u')
            ->leftJoin('t.lease', 'l')
            ->addSelect('l')
            ->where('l.property = :property')
            ->setParameter('property', $property)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWithUser(int $id): ?Tenant
    {
        return $this->createQueryBuilder('t')
        ->join('t.user', 'u')
        ->addSelect('u')
        ->leftJoin('u.owner', 'o')
        ->addSelect('o')
        ->where('t.id =:id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();
    }
}
