<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AnswerRepository.
 *
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
     * @param Answer $answer Answer entity
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


    public function findMostLikedAnswer(int $questionId): ?Answer
    {
        return $this->createQueryBuilder('a')
            ->join('a.votes', 'v')
            ->where('a.question = :qid')
            ->setParameter('qid', $questionId)
            ->groupBy('a.id')
            ->orderBy('COUNT(v.id)', 'DESC')
            ->addOrderBy('a.createdAt', 'DESC')
            ->addOrderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
