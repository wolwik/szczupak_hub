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

use App\Entity\Question;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface QuestionServiceInterface.
 */
interface QuestionServiceInterface
{
    /**
     * Get paginated list of question-photos.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Category ID filter
     * @param int|null $tagId      Tag ID filter
     *
     * @return PaginationInterface Pagination interface
     */
    public function getPaginatedList(int $page, ?int $categoryId = null, ?int $tagId = null): PaginationInterface;

    /**
     * Get user drafts.
     *
     * @param User $user User entity
     *
     * @return array List of user drafts
     */
    public function getUserDrafts(User $user): array;

    /**
     * Save question.
     *
     * @param Question $question Question entity
     */
    public function save(Question $question): void;

    /**
     * Delete question.
     *
     * @param Question $question Question entity
     */
    public function delete(Question $question): void;
}
