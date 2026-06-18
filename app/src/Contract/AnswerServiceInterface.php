<?php

/**
 * Answer service interface.
 */

namespace App\Contract;

use App\Entity\Answer;
use App\Entity\Question;

/**
 * Interface AnswerServiceInterface.
 */
interface AnswerServiceInterface
{
    /**
     * Save answer.
     *
     * @param Answer        $answer   Answer entity
     * @param Question|null $question Related question entity
     */
    public function save(Answer $answer, ?Question $question = null): void;

    /**
     * Delete answer.
     *
     * @param Answer $answer Answer entity
     */
    public function delete(Answer $answer): void;

    /**
     * Mark answer as the best one.
     *
     * @param Answer $answer Answer entity
     */
    public function markAsBest(Answer $answer): void;

    /**
     * Update best answer for a question.
     *
     * @param Question $question Question entity
     */
    public function updateBestAnswer(Question $question): void;
}
