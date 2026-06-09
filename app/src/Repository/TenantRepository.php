<?php

namespace App\Repository;

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
