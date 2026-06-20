<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig\Extension;

use App\Entity\Question;
use App\Repository\QuestionPhotoRepository;
use Twig\Attribute\AsTwigFunction;
use App\Contract\AnswerServiceInterface;
use App\Entity\Answer;
use App\Entity\User;
use App\Repository\AvatarRepository;

/**
 * Class AppExtension.
 */
class AppExtension
{
    /**
     * Constructor.
     *
     * @param AvatarRepository        $avatarRepository        Avatar repository
     * @param AnswerServiceInterface  $answerService           Answer service interface
     * @param QuestionPhotoRepository $questionPhotoRepository Question photo repository
     */
    public function __construct(private readonly AvatarRepository $avatarRepository, private readonly AnswerServiceInterface $answerService, private readonly QuestionPhotoRepository $questionPhotoRepository)
    {
    }

    /**
     * Get user avatar filename.
     *
     * @param User|null $user User entity
     *
     * @return string|null Avatar filename or null
     */
    #[AsTwigFunction(name: 'get_user_avatar')]
    public function getUserAvatar(?User $user): ?string
    {
        if (!$user) {
            return null;
        }

        $avatar = $this->avatarRepository->findOneByUser($user);

        return $avatar?->getFilename();
    }

    /**
     * Get answer votes count.
     *
     * @param Answer $answer Answer entity
     *
     * @return int Votes count
     */
    #[AsTwigFunction(name: 'get_answer_votes')]
    public function getAnswerVotes(Answer $answer): int
    {
        return $this->answerService->getVotesCount($answer);
    }

    /**
     * Get question photo filename.
     *
     * @param Question|null $question Question entity
     *
     * @return string|null Photo filename or null
     */
    #[AsTwigFunction(name: 'get_question_photo')]
    public function getQuestionPhoto(?Question $question): ?string
    {
        if (!$question) {
            return null;
        }

        $photo = $this->questionPhotoRepository->findOneByQuestion($question);

        return $photo?->getFilename();
    }
}
