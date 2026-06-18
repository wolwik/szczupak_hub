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
use App\Entity\User;

/**
 * Interface VoteServiceInterface.
 */
interface VoteServiceInterface
{
    /**
     * Cast a vote for an answer.
     *
     * @param Answer $answer Answer entity
     * @param User   $user   User entity
     */
    public function vote(Answer $answer, User $user): void;
}
