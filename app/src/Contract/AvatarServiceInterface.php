<?php

/**
 * Avatar service interface.
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
     * @param UploadedFile  $uploadedFile Uploaded file
     * @param Avatar        $avatar       Avatar entity
     * @param User $user                  User
     */
    public function create(UploadedFile $uploadedFile, Avatar $avatar, User $user): void;

    /**
     * Update avatar.
     *
     * @param UploadedFile  $uploadedFile Uploaded file
     * @param Avatar        $avatar       Avatar entity
     * @param User $user         User interface
     */
    public function update(UploadedFile $uploadedFile, Avatar $avatar, User $user): void;

    /**
     * Delete avatar.
     *
     * @param User $user User interface
     */
    public function delete(User $user): void;

}


