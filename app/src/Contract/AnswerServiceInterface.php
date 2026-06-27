<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    /**
     * Get single answer votes count.
     *
     * Triggered automatically after vote modifications.
     *
     * @param Answer $answer Answer entity
     */
    public function getVotesCount(Answer $answer): int;

    /**
     * Get votes count map for multiple answers.
     *
     * @param array $answers Array of Answer entities or collection
     *
     * @return array<int, int> Map of [answerId => voteCount]
     */
    public function getVotesMapForAnswers(array $answers): array;
}
