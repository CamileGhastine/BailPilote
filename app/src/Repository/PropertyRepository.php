<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function findWithLeaseAndTenants(int $id): ?Property
    {
        return $this->createQueryBuilder('p')
        ->join('p.lease', 'l')
        ->addSelect('l')
        ->leftJoin('l.tenant', 't')
        ->addSelect('t')
        ->leftJoin('t.user', 'u')
        ->addSelect('u')
        ->leftJoin('u.owner', 'o')
        ->addSelect('o')
        ->where('p.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();
    }
}
