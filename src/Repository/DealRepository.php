<?php

namespace App\Repository;

use App\Entity\Deal;
use App\Enum\DealStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deal>
 */
class DealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deal::class);
    }

    //    /**
    //     * @return Deal[] Returns an array of Deal objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Deal
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



    public function findByCriteria(array $criteria)
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->leftJoin('d.categories', 'c') 
            ->addSelect('c'); 

        
        if (isset($criteria['name'])) {
            $queryBuilder->andWhere('d.name LIKE :name')
                ->setParameter('name', $criteria['name']);
        }

        
        if (isset($criteria['enable'])) {
            $queryBuilder->andWhere('d.enable = :enable')
                ->setParameter('enable', $criteria['enable']);
        }

        
        if (isset($criteria['category'])) {
            $queryBuilder->andWhere('c.id = :category')
                ->setParameter('category', $criteria['category']->getId()); 
        }

        return $queryBuilder->getQuery()->getResult();
    }


    public function findRelatedDealsByCategories($categories, $currentDealId, $limit = 6)
    {
        $qb = $this->createQueryBuilder('d')
            ->innerJoin('d.categories', 'c') // Association avec la table des catégories
            ->where('c IN (:categories)')
            ->andWhere('d.id != :currentDealId') // Exclure le deal actuel
            ->setParameter('categories', $categories)
            ->setParameter('currentDealId', $currentDealId)
            ->setMaxResults($limit)
            ->orderBy('d.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function findHotestDeals($limit)
    {
        $activeStatus = DealStatusEnum::ACTIVE;

        $qb = $this->createQueryBuilder('d')
            ->where('d.status = :activeStatus')
            ->setParameter('activeStatus', $activeStatus)
            ->orderBy('d.hotScore', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
