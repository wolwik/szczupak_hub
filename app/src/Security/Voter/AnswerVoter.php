<?php

namespace App\Security\Voter;

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class AnswerVoter extends Voter
{
    public const EDIT = 'ANSWER_EDIT';

    public const DELETE = 'ANSWER_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Answer;
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token
    ): bool {
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
            default => false,
        };
    }

    private function canEdit(Answer $answer, UserInterface $user): bool
    {

        // admin ma zawsze dostęp
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }
        //  owner ma dostęp
        return $answer->getAuthor() === $user;
    }

    private function canDelete(Answer $answer, UserInterface $user): bool
    {
        // admin ma zawsze dostęp
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // owner ma dostęp
        return $answer->getAuthor() === $user;
    }

}
