<?php

/**
 * Vote service interface.
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
