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

use App\Entity\Avatar;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface AvatarServiceInterface.
 */
interface AvatarServiceInterface
{
    /**
     * Create avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Avatar       $avatar       Avatar entity
     * @param User         $user         User
     */
    public function create(UploadedFile $uploadedFile, Avatar $avatar, User $user): void;

    /**
     * Update avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Avatar       $avatar       Avatar entity
     * @param User         $user         User interface
     */
    public function update(UploadedFile $uploadedFile, Avatar $avatar, User $user): void;

    /**
     * Delete avatar.
     *
     * @param User $user User interface
     */
    public function delete(User $user): void;
}
