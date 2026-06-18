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
 * Enum QuestionStatus.
 */
enum QuestionStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    /**
     * Get the status label.
     *
     * @return string Status label
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
        };
    }
}
