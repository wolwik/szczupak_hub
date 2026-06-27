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

use App\Entity\Category;
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
     * @param int|null $categoryId Category ID filter
     * @param int|null $tagId      Tag ID filter
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(?int $categoryId = null, ?int $tagId = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('question')
            ->leftJoin('question.category', 'category')
            ->addSelect('category')
            ->leftJoin('question.author', 'author')
            ->addSelect('author')
            ->leftJoin('App\Entity\Avatar', 'avatar', 'WITH', 'avatar.user = author.id')
            ->addSelect('avatar')
            ->leftJoin('App\Entity\QuestionPhoto', 'questionPhoto', 'WITH', 'questionPhoto.question = question.id')
            ->addSelect('questionPhoto')
            ->leftJoin('question.tags', 'tags')
            ->addSelect('tags')
            ->where('question.status = :status')
            ->setParameter('status', QuestionStatus::PUBLISHED);

        if ($categoryId) {
            $qb->andWhere('category.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        if ($tagId) {
            $qb->andWhere('tags.id = :tagId')
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
            ->leftJoin('question.category', 'category')
            ->addSelect('category')
            ->leftJoin('question.tags', 'tags')
            ->addSelect('tags')
            ->where('question.author = :user')
            ->andWhere('question.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', QuestionStatus::DRAFT)
            ->orderBy('question.createdAt', 'DESC');
    }

    /**
     * Displays all user's question-photos (currently not used).
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

    /**
     * Count question-photos by category.
     *
     * @param Category $category Category entity
     *
     * @return int Number of question-photos
     */
    public function countByCategory(Category $category): int
    {
        return (int) $this->createQueryBuilder('question')
            ->select('count(question.id)')
            ->where('question.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
