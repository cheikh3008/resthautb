<?php

namespace App\Repository;

use App\Entity\PlatCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlatCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlatCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlatCommande[]    findAll()
 * @method PlatCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlatCommande::class);
    }

    // /**
    //  * @return PlatCommande[] Returns an array of PlatCommande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlatCommande
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
