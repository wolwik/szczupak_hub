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

use App\Entity\QuestionPhoto;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class QuestionPhotoRepository.
 *
 * @extends ServiceEntityRepository<QuestionPhoto>
 */
class QuestionPhotoRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionPhoto::class);
    }

    /**
     * Find one by question.
     *
     * @param Question $question Question entity
     *
     * @return QuestionPhoto|null Question photo entity
     */
    public function findOneByQuestion(Question $question): ?QuestionPhoto
    {
        return $this->findOneBy(['question' => $question]);
    }

    /**
     * Save entity.
     *
     * @param QuestionPhoto $photo Question photo entity
     */
    public function save(QuestionPhoto $photo): void
    {
        $this->getEntityManager()->persist($photo);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param QuestionPhoto $photo Question photo entity
     */
    public function delete(QuestionPhoto $photo): void
    {
        $this->getEntityManager()->remove($photo);
        $this->getEntityManager()->flush();
    }
}
