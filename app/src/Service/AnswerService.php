<?php

/**
 * Answer service
 */

namespace App\Service;

use App\Contract\AnswerServiceInterface;
use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;

class AnswerService implements AnswerServiceInterface
{

    public function __construct(
        private readonly AnswerRepository $answerRepository,
        private readonly QuestionRepository $questionRepository
    ) {}


    public function save(Answer $answer, ?Question $question = null): void
    // par Question jest opcjonalny (ta metoda jest też uzywana do edytowania pytania)
    {
        if (null === $answer->getId()) {
            $answer->setCreatedAt(new \DateTime);
            $answer->setQuestion($question);
        } else {
            $answer->setUpdatedAt(new \DateTime); // jeżeli nie null to update
        }

        $this->answerRepository->save($answer);
    }


    public function delete(Answer $answer): void
    {
        $question = $answer->getQuestion();

        $this->answerRepository->delete($answer);

        if ($question) {
            $this->updateBestAnswer($question);
        }
    }


    public function markAsBest(Answer $answer): void
    {
        $question = $answer->getQuestion();
        $question->setBestAnswer($answer);
        $this->questionRepository->save($question);
    }

    // wywoływane przez VoteService po każdym like
    public function updateBestAnswer(Question $question): void
    {
        $bestAnswer = $this->answerRepository->findMostLikedAnswer($question->getId());

        $question->setBestAnswer($bestAnswer);

        $this->questionRepository->save($question);
    }

}






