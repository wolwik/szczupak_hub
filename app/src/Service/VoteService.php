<?php

/**
 * Vote service
 */

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use App\Entity\Vote;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Repository\VoteRepository;
use App\Service\AnswerService;



class VoteService
{
    public function __construct(
        private VoteRepository $voteRepository,
        private AnswerRepository $answerRepository,
        private AnswerService $answerService
    ) {}

    // VOTING
    public function vote(Answer $answer, User $user): void
    {
        // zabezpieczenie przed ponownym głosowaniem
        $isVoted = $this->voteRepository->findOneBy([
            'answer' => $answer,
            'user' => $user
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
