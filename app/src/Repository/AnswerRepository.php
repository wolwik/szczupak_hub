<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Answer>
 */
class AnswerRepository extends ServiceEntityRepository
{

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }


    /**
     * Save entity.
     *
     * @param Answer
     */

    public function save(Answer $answer): void
    {
        $this->getEntityManager()->persist($answer);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Answer $answer Answer entity
     */

    public function delete(Answer $answer): void
    {
        $this->getEntityManager()->remove($answer);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return Answer[] Returns an array of Answer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Answer
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
