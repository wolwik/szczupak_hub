<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Vote;
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

    /**
     * Find the most liked answer.
     *
     * @param int $questionId Question ID
     *
     * @return Answer|null Most liked answer or null
     */
    public function findMostLikedAnswer(int $questionId): ?Answer
    {
        return $this->createQueryBuilder('a')
            ->join(Vote::class, 'v', 'WITH', 'v.answer = a.id')
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
