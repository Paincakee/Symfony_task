<?php

namespace App\Repository;

use App\Entity\TaskCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskCategories>
 *
 * @method TaskCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskCategories[]    findAll()
 * @method TaskCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskCategories::class);
    }

    //    /**
    //     * @return TaskCategories[] Returns an array of TaskCategories objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TaskCategories
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
