<?php

namespace App\Repository;

use App\Entity\SmsStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SmsStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmsStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmsStatus[]    findAll()
 * @method SmsStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmsStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmsStatus::class);
    }

    // /**
    //  * @return SmsStatus[] Returns an array of SmsStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SmsStatus
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
