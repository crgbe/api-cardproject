<?php

namespace App\Repository;

use App\Entity\CardGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CardGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardGroup[]    findAll()
 * @method CardGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardGroup::class);
    }

    // /**
    //  * @return CardGroup[] Returns an array of CardGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CardGroup
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
