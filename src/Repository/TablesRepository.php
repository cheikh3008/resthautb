<?php

namespace App\Repository;

use App\Entity\Tables;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Tables|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tables|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tables[]    findAll()
 * @method Tables[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TablesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tables::class);
    }
    public function findTablesByResto($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT T.id, T.numero, T.nbPersonne FROM App\Entity\Tables T, 
            App\Entity\Resto R
            WHERE T.resto = R.id  AND R.id = '.$id
        )->getResult();
    }
    // public function getTables()
    // {
    //     return $this->createQueryBuilder('t')
    //                 ->innerJoin('t.reservation', 'r')
    //                 ->innerJoin('t.resto', 'res')
    //                 ->where('r.id = t.id')
    //                 ->andWhere('res.id = :resto_id')
    //                 ->setParameter('resto_id', 28)
    //                 ->getQuery()
    //                 ->getResult();
    // }
    // /**
    //  * @return Tables[] Returns an array of Tables objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tables
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
