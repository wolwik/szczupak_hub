<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use App\Entity\Enum\QuestionStatus;
use App\Entity\Question;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class QuestionVoter.
 */
final class QuestionVoter extends Voter
{
    /**
     * Edit permission.
     *
     * @var string
     */
    public const EDIT = 'QUESTION_EDIT';

    /**
     * Delete permission.
     *
     * @var string
     */
    public const DELETE = 'QUESTION_DELETE';

    /**
     * View draft permission.
     *
     * @var string
     */
    public const VIEW = 'QUESTION_VIEW';

    /**
     * Determines if this voter supports the attribute and subject.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool Result
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::VIEW])
            && $subject instanceof Question;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute Permission name
     * @param mixed          $subject   Object
     * @param TokenInterface $token     Security token
     * @param Vote|null      $vote      Optional vote parameter
     *
     * @return bool Vote result
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$subject instanceof Question) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            self::VIEW => $this->canView($subject, $user),
            default => false,
        };
    }

    /**
     * Checks if user can edit question.
     *
     * @param Question      $question Question entity
     * @param UserInterface $user     User
     *
     * @return bool Result
     */
    private function canEdit(Question $question, UserInterface $user): bool
    {
        // admin ma zawsze dostęp
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // owner ma dostęp
        return $question->getAuthor() === $user;
    }

    /**
     * Checks if user can delete question.
     *
     * @param Question      $question Question entity
     * @param UserInterface $user     User
     *
     * @return bool Result
     */
    private function canDelete(Question $question, UserInterface $user): bool
    {
        // admin ma zawsze dostęp
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // owner ma dostęp
        return $question->getAuthor() === $user;
    }

    /**
     * Checks if user can view question draft.
     *
     * @param Question      $question Question entity
     * @param UserInterface $user     User
     *
     * @return bool Result
     */
    private function canView(Question $question, UserInterface $user): bool
    {
        // opublikowane pytania są publiczne
        if (QuestionStatus::PUBLISHED === $question->getStatus()) {
            return true;
        }

        // tylko owner ma dostęp
        return $question->getAuthor() === $user;
    }
}
