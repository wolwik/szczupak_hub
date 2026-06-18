<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Enum;

/**
 * Enum UserRole.
 */
enum UserRole: string
{
    case ROLE_USER = 'ROLE_USER';
    case ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Get the role label.
     *
     * @return string Role label
     */
    public function label(): string
    {
        return match ($this) {
            UserRole::ROLE_USER => 'label.role_user',
            UserRole::ROLE_ADMIN => 'label.role_admin',
        };
    }
}
