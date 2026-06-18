<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @param User   $user          User entity
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
