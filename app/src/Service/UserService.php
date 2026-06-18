<?php

/**
 * User service.
 */

namespace App\Service;

use App\Contract\UserServiceInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/*
 * Class UserService
 */

class UserService implements UserServiceInterface
{
    /**
     * Constructor.
     *
     * @param UserRepository              $userRepository UserRepository
     * @param UserPasswordHasherInterface $passwordHasher PasswordHasher
     */
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * Save user.
     *
     * @param User $user User entity
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Register a new user.
     *
     * @param User   $user          User entity
     * @param string $plainPassword Plain text password
     *
     * @throws \Exception If email or nickname already exists
     */
    public function register(User $user, string $plainPassword): void
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

    /**
     * Delete user.
     *
     * @param User $user User entity
     */
    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }

}
