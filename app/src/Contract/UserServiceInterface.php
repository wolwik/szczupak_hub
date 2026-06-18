<?php
/**
 * User service interface.
 */

namespace App\Contract;

use App\Entity\User;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Save user.
     *
     * @param User $user User entity
     */
    public function save(User $user): void;

    /**
     * Register a new user.
     *
     * @param User $user User entity
     * @param string $plainPassword Plain text password
     *
     * @throws \Exception If email or nickname already exists
     */
    public function register(User $user, string $plainPassword): void;

    /**
     * Delete user.
     *
     * @param User $user User entity
     */
    public function delete(User $user): void;
}
