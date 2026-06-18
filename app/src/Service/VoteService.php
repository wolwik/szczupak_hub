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

use App\Contract\VoteServiceInterface;
use App\Entity\Answer;
use App\Entity\User;
use App\Entity\Vote;
use App\Repository\VoteRepository;
use App\Contract\AnswerServiceInterface;

/**
 * Class VoteService.
 */
class VoteService implements VoteServiceInterface
{
    /**
     * Constructor.
     *
     * @param VoteRepository         $voteRepository Vote repository
     * @param AnswerServiceInterface $answerService  Answer service interface
     */
    public function __construct(private readonly VoteRepository $voteRepository, private readonly AnswerServiceInterface $answerService)
    {
    }

    /**
     * Cast a vote for an answer.
     *
     * @param Answer $answer Answer entity
     * @param User   $user   User entity
     */
    public function vote(Answer $answer, User $user): void
    {
        // zabezpieczenie przed ponownym głosowaniem
        $isVoted = $this->voteRepository->findOneBy([
            'answer' => $answer,
            'user' => $user,
        ]);

        if ($isVoted) {
            return;
        }

        $vote = new Vote();

        $vote->setAnswer($answer);
        $vote->setUser($user);

        $this->voteRepository->save($vote);

        // ustalenie najlepszej odpowiedzi
        $question = $answer->getQuestion();
        $this->answerService->updateBestAnswer($question);
    }
}
