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
use App\Entity\Answer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AnswerVoter.
 */
final class AnswerVoter extends Voter
{
    /**
     * Edit permission.
     *
     * @var string
     */
    public const EDIT = 'ANSWER_EDIT';

    /**
     * Delete permission.
     *
     * @var string
     */
    public const DELETE = 'ANSWER_DELETE';

    /**
     * Mark-as-best permission.
     *
     * @var string
     */
    public const MARK_AS_BEST = 'ANSWER_MARK_AS_BEST';

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
        return in_array($attribute, [self::EDIT, self::DELETE, self::MARK_AS_BEST])
            && $subject instanceof Answer;
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

        if (!$subject instanceof Answer) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            self::MARK_AS_BEST => $this->canMarkAsBest($subject, $user),
            default => false,
        };
    }

    /**
     * Checks if user can edit answer.
     *
     * @param Answer        $answer Answer entity
     * @param UserInterface $user   User
     *
     * @return bool Result
     */
    private function canEdit(Answer $answer, UserInterface $user): bool
    {
        // admin ma zawsze dostęp
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        //  owner ma dostęp
        return $answer->getAuthor() === $user;
    }

    /**
     * Checks if user can delete answer.
     *
     * @param Answer        $answer Answer entity
     * @param UserInterface $user   User
     *
     * @return bool Result
     */
    private function canDelete(Answer $answer, UserInterface $user): bool
    {
        // admin ma zawsze dostęp
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // owner ma dostęp
        return $answer->getAuthor() === $user;
    }

    /**
     * Checks if user can mark answer as the best one.
     *
     * @param Answer        $answer Answer entity
     * @param UserInterface $user   User
     *
     * @return bool Result
     */
    private function canMarkAsBest(Answer $answer, UserInterface $user): bool
    {
        return $answer
                ->getQuestion()
                ->getAuthor() === $user;
    }
}
