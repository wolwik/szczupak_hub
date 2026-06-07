<?php

namespace App\Security\Voter;

use App\Entity\Question;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class QuestionVoter extends Voter
{
    public const EDIT = 'QUESTION_EDIT';

    public const DELETE = 'QUESTION_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Question;
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

        if (!$subject instanceof Question) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            default => false,
        };
    }


    private function canEdit(Question $question, UserInterface $user): bool
    {
        // admin ma zawsze dostęp
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // owner ma dostęp
        return $question->getAuthor() === $user;
    }

    private function canDelete(Question $question, UserInterface $user): bool
    {
        // admin ma zawsze dostęp
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // owner ma dostęp
        return $question->getAuthor() === $user;
    }
}



