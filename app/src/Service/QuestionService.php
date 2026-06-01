<?php

/**
 * Question service.
 */

namespace App\Service;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/*
 * Class QuestionService
 */

class QuestionService {
    private const PAGINATOR_ITEMS_PER_PAGE = 10;
    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly PaginatorInterface $paginator
    ) {}

    public function getPaginatedList(int $page, ?int $categoryId = null): PaginationInterface {
        return $this->paginator->paginate(
            $this->questionRepository->queryAll($categoryId),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['question.createdAt', 'question.title'], // lista rzeczy,
                // po ktorych bedzie mozna sortowac
                'defaultSortFieldName' => 'question.createdAt', // domyslne sortowanie
                'defaultSortDirection' => 'desc', // kierunek sortowania, descending
            ]
        );
    }
    public function save(Question $question): void
    {
        $question->setUpdatedAt(new \DateTime);
        if (null === $question->getId()) {
            $question->setCreatedAt(new \DateTime);
        }
        $this->questionRepository->save($question);
    }

    public function delete(Question $question): void
    {
        $this->questionRepository->delete($question);
    }




}
