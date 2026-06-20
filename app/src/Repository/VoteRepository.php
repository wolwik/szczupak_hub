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

use App\Entity\Answer;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class VoteRepository.
 *
 * @extends ServiceEntityRepository<Vote>
 */
class VoteRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    /**
     * Save entity.
     *
     * @param Vote $vote Vote entity
     */
    public function save(Vote $vote): void
    {
        $this->getEntityManager()->persist($vote);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Vote $vote Vote entity
     */
    public function delete(Vote $vote): void
    {
        $this->getEntityManager()->remove($vote);
        $this->getEntityManager()->flush();
    }

    /**
     * Count votes for a single answer.
     *
     * @param Answer $answer Answer entity
     *
     * @return int Number of votes for the answer
     */
    public function countVotesForAnswer(Answer $answer): int
    {
        return (int) $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.answer = :answer')
            ->setParameter('answer', $answer)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
