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

}
