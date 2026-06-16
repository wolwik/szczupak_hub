<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/**
 *  Class UserChecker
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * Checks user before authentication.
     *
     * @param UserInterface $user The authenticated user instance
     *
     * @return void
     *
     * @throws CustomUserMessageAuthenticationException When user account is blocked
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->getisBlocked()) {
            throw new CustomUserMessageAuthenticationException(
                'Twoje konto zostało zablokowane.'
            );
        }
    }


    /**
     * Checks user after authentication.
     *
     * @param UserInterface $user The authenticated user instance
     *
     * @return void
     */
    public function checkPostAuth(UserInterface $user): void
    {
        // No post-auth checks implemented
    }
}




