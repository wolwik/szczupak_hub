<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class VoteRepository
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

}
