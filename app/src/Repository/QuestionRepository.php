<?php

namespace App\Repository;

use App\Entity\Enum\QuestionStatus;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class QuestionRepository.
 *
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }


    /**
     * Query all records.
     *
     * @param int|null $categoryId
     * @param int|null $tagId
     *
     * @return QueryBuilder Query builder
     */

    public function queryAll(
        ?int $categoryId = null,
        ?int $tagId = null,
    ): QueryBuilder
    {
        $qb = $this->createQueryBuilder('question')
            ->leftJoin('question.category', 'category')
            ->addSelect('category')
            ->where('question.status = :status')
            ->setParameter('status', QuestionStatus::PUBLISHED);

        if ($categoryId) {
            $qb->andWhere('category.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        if ($tagId) {
            $qb->join('question.tags', 'tag')
                ->andWhere('tag.id = :tagId')
                ->setParameter('tagId', $tagId);
        }

        return $qb;
    }


    /**
     * Displays user drafts.
     *
     * @param User $user User entity
     *
     * @return QueryBuilder QueryBuilder
     */

    public function queryUserDrafts(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('question')
            ->where('question.author = :user')
            ->andWhere('question.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', QuestionStatus::DRAFT)
            ->orderBy('question.createdAt', 'DESC');
    }


    /**
     * Displays all user's questions (currently not used)
     *
     * @param User $user User entity
     *
     * @return QueryBuilder QueryBuilder
     */

    public function findByUser(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('question')
            ->where('question.author = :user')
            ->setParameter('user', $user);
    }


    /**
     * Save entity.
     *
     * @param Question $question Question entity
     */

    public function save(Question $question): void
    {
        $this->getEntityManager()->persist($question);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Question $question Question entity
     * @param bool     $flush    Bool for deleting entity
     */

    public function delete(Question $question, bool $flush = true): void
    {
        $this->getEntityManager()->remove($question);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
