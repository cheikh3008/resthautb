<?php

namespace App\Repository;

use App\Entity\Resto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Resto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resto[]    findAll()
 * @method Resto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resto::class);
    }
    public function findUserById($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT R FROM App\Entity\User U, 
            App\Entity\Resto R
            WHERE U.id = R.user  AND U.id = '.$id
        )->getResult();
    }
    public function findRestoById($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT R.id, R.nomResto, R.description, R.adresse, R.image, U.telephone, U.username FROM App\Entity\User U, 
            App\Entity\Resto R
            WHERE U.id = R.user  AND R.id = '.$id
        )->getSingleResult();
    }
    
    // public function findRestoById($id)
    // {
    //     $em = $this->getEntityManager();
    //     $query = $em->createQuery('SELECT DISTINCT R.id FROM App\Entity\User U, 
    //         App\Entity\Resto R
    //         WHERE U.id = R.user  AND U.id = '.$id);
    //     $query->setParameter('id', $id);
    //     return $query->getResult();;
    // }
    // /**
    //  * @return Resto[] Returns an array of Resto objects
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
    public function findOneBySomeField($value): ?Resto
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
