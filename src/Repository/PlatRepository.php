<?php

namespace App\Repository;

use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Resto;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Plat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plat[]    findAll()
 * @method Plat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plat::class);
    }
    public function findRestoById($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT M.id, M.categorie, M.image, P.id, P.nomPlat, P.description, P.prix FROM App\Entity\Menu M, 
            App\Entity\Plat P, App\Entity\Resto R
            WHERE R.id = P.resto AND M.id = P.menu AND R.id = ' . $id
            )->getArrayResult();
    }
    
    // /**
    //  * @return Plat[] Returns an array of Plat objects
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
    public function findOneBySomeField($value): ?Plat
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
