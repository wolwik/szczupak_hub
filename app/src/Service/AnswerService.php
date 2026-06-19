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

use App\Contract\AnswerServiceInterface;
use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Repository\VoteRepository;

/**
 * Class AnswerService.
 */
class AnswerService implements AnswerServiceInterface
{
    /**
     * Constructor.
     *
     * @param AnswerRepository   $answerRepository   Answer repository
     * @param QuestionRepository $questionRepository Question repository
     * @param VoteRepository     $voteRepository     Vote repository
     */
    public function __construct(private readonly AnswerRepository $answerRepository, private readonly QuestionRepository $questionRepository, private readonly VoteRepository $voteRepository)
    {
    }

    /**
     * Save answer entity.
     *
     * The $question parameter is optional (used when creating, omitted during edit).
     *
     * @param Answer        $answer   Answer entity
     * @param Question|null $question Question entity (optional)
     */
    public function save(Answer $answer, ?Question $question = null): void
    {
        if (null === $answer->getId()) {
            $answer->setCreatedAt(new \DateTime());
            $answer->setQuestion($question);
        } else {
            $answer->setUpdatedAt(new \DateTime()); // jeżeli nie null to update
        }

        $this->answerRepository->save($answer);
    }

    /**
     * Delete answer entity.
     *
     * @param Answer $answer Answer entity
     */
    public function delete(Answer $answer): void
    {
        $question = $answer->getQuestion();

        $this->answerRepository->delete($answer);

        if ($question) {
            $this->updateBestAnswer($question);
        }
    }

    /**
     * Mark an answer as the best answer.
     *
     * @param Answer $answer Answer entity
     */
    public function markAsBest(Answer $answer): void
    {
        $question = $answer->getQuestion();
        $question->setBestAnswer($answer);
        $this->questionRepository->save($question);
    }

    /**
     * Update the best answer for a given question based on votes.
     *
     * Triggered automatically after vote modifications.
     *
     * @param Question $question Question entity
     */
    public function updateBestAnswer(Question $question): void
    {
        $bestAnswer = $this->answerRepository->findMostLikedAnswer($question->getId());

        $question->setBestAnswer($bestAnswer);

        $this->questionRepository->save($question);
    }

    /**
     * Get single answer votes count.
     *
     * Triggered automatically after vote modifications.
     *
     * @param Answer $answer Answer entity
     *
     * @return int Votes count for answer
     */
    public function getVotesCount(Answer $answer): int
    {
        return $this->voteRepository->countVotesForAnswer($answer);
    }
}
