<?php

/**
 * Avatar service.
 */

namespace App\Service;

use App\Contract\AvatarServiceInterface;
use App\Contract\FileUploadServiceInterface;
use App\Entity\Avatar;
use App\Entity\User;
use App\Repository\AvatarRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AvatarService.
 */
class AvatarService implements AvatarServiceInterface
{
    /**
     * Constructor.
     *
     * @param string                     $targetDirectory   Target directory
     * @param AvatarRepository           $avatarRepository  Avatar repository
     * @param FileUploadServiceInterface $fileUploadService File upload service
     * @param Filesystem                 $filesystem        Filesystem component
     */
    public function __construct(
        string $targetDirectory,
        AvatarRepository $avatarRepository,
        FileUploadServiceInterface $fileUploadService,
        Filesystem $filesystem
    ) {}

    /**
     * Update avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Avatar       $avatar       Avatar entity
     * @param User         $user         User entity
     */
    public function update(UploadedFile $uploadedFile, Avatar $avatar, User $user): void
    {
        $oldFilename = $avatar->getFilename();

        if ($oldFilename) {
            $this->filesystem->remove(
                $this->targetDirectory . '/' . $oldFilename
            );
        }

        $newFilename = $this->fileUploadService->upload($uploadedFile);

        $avatar->setFilename($newFilename);
        $avatar->setUser($user);

        $this->avatarRepository->save($avatar);

    }

    /**
     * Create avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Avatar       $avatar       Avatar entity
     * @param User         $user         User entity
     */
    public function create(UploadedFile $uploadedFile, Avatar $avatar, User $user): void
    {
        $avatarFilename = $this->fileUploadService->upload($uploadedFile);

        $avatar->setUser($user);
        $avatar->setFilename($avatarFilename);
        $this->avatarRepository->save($avatar);
    }

    /**
     * Delete avatar.
     *
     * @param User $user User interface
     */
    public function delete(User $user): void
    {
        $avatar = $user->getAvatar();

        if (!$avatar) {
            return;
        }

        $filename = $avatar->getFilename();

        if ($filename) {
            $this->filesystem->remove(
                $this->targetDirectory . '/' . $filename
            );
        }

        $user->setAvatar(null);

        $this->avatarRepository->delete($avatar);
    }

}
