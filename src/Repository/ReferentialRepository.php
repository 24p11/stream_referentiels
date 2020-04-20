<?php

namespace App\Repository;

use App\Entity\Referential;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Referential|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referential|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referential[]    findAll()
 * @method Referential[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferentialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referential::class);
    }

    // /**
    //  * @return Referential[] Returns an array of Referential objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Referential
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function whereIn(string $referential, array $ids)
    {
        return $this->createQueryBuilder('r', 'r.refId')
            ->where('r.type = :referential')
            ->setParameter(':referential', $referential)
            ->andWhere('r.refId IN (:ids)')
            ->setParameter('ids', $ids);
    }
}
