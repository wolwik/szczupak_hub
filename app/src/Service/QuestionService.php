<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Contract\QuestionServiceInterface;
use App\Entity\Question;
use App\Entity\User;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class QuestionService.
 */
class QuestionService implements QuestionServiceInterface
{
    /**
     * Paginator items per page.
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param QuestionRepository $questionRepository Question repository
     * @param AnswerRepository   $answerRepository   Answer repository
     * @param PaginatorInterface $paginator          Paginator interface
     */
    public function __construct(private readonly QuestionRepository $questionRepository, private readonly AnswerRepository $answerRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list of question-photos.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Category ID filter
     * @param int|null $tagId      Tag ID filter
     *
     * @return PaginationInterface Paginated collection
     */
    public function getPaginatedList(int $page, ?int $categoryId = null, ?int $tagId = null): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->questionRepository->queryAll($categoryId, $tagId),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['question.createdAt', 'question.title'],
                'defaultSortFieldName' => 'question.createdAt',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * Get answers for a specific question.
     *
     * @param Question $question Question entity
     *
     * @return array List of answers
     */
    public function getAnswersForQuestion(Question $question): array
    {
        return $this->answerRepository->findBy(
            ['question' => $question],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Get drafts belonging to a specific user.
     *
     * @param User $user User entity
     *
     * @return array<int, Question> List of user drafts
     */
    public function getUserDrafts(User $user): array
    {
        return $this->questionRepository
            ->queryUserDrafts($user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Save question entity.
     *
     * @param Question $question Question entity
     */
    public function save(Question $question): void
    {
        $question->setUpdatedAt(new \DateTime());
        if (null === $question->getId()) {
            $question->setCreatedAt(new \DateTime());
        }
        $this->questionRepository->save($question);
    }

    /**
     * Delete question entity.
     *
     * @param Question $question Question entity
     */
    public function delete(Question $question): void
    {
        $this->questionRepository->delete($question);
    }
}
