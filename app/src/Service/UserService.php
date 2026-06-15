<?php

/**
 * User service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/*
 * Class UserService
 */

class UserService {

    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }


    public function register(
        User $user,
        string $plainPassword
    ): void
    {
        if ($this->userRepository->findByEmail($user->getEmail())) {
            throw new \Exception('Email already exists');
        }

        if ($this->userRepository->findByNickname($user->getNickname())) {
            throw new \Exception('Nickname already exists');
        }

        $hashedPassword = $this->passwordHasher
            ->hashPassword(
                $user,
                $plainPassword
            );
        $user->setPassword($hashedPassword);

        $user->setRoles([
            'ROLE_USER'
        ]);

        $this->userRepository->save($user);

    }

    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }

}
