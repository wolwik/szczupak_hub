<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Wolwik / UJ
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Contract\QuestionPhotoServiceInterface;
use App\Contract\FileUploadServiceInterface;
use App\Entity\QuestionPhoto;
use App\Entity\Question;
use App\Repository\QuestionPhotoRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class QuestionPhotoService.
 */
class QuestionPhotoService implements QuestionPhotoServiceInterface
{
    /**
     * QuestionPhotoService constructor.
     *
     * @param string                     $questionsTargetDirectory the target directory where question photos are stored
     * @param QuestionPhotoRepository    $photoRepository          the repository for managing photo database operations
     * @param FileUploadServiceInterface $fileUploadService        the service responsible for handling file uploads
     * @param Filesystem                 $filesystem               the Symfony Filesystem component for file system operations
     */
    public function __construct(private readonly string $questionsTargetDirectory, private readonly QuestionPhotoRepository $photoRepository, private readonly FileUploadServiceInterface $fileUploadService, private readonly Filesystem $filesystem)
    {
    }

    /**
     * Finds a single photo associated with the given question.
     *
     * @param Question $question the question entity
     *
     * @return QuestionPhoto|null the question photo entity, or null if not found
     */
    public function findOneByQuestion(Question $question): ?QuestionPhoto
    {
        return $this->photoRepository->findOneByQuestion($question);
    }

    /**
     * Creates and saves a new question photo.
     *
     * @param UploadedFile  $uploadedFile the uploaded file instance
     * @param QuestionPhoto $photo        the question photo entity to populate
     * @param Question      $question     the associated question entity
     */
    public function create(UploadedFile $uploadedFile, QuestionPhoto $photo, Question $question): void
    {
        $filename = $this->fileUploadService->upload($uploadedFile, $this->questionsTargetDirectory);

        $photo->setQuestion($question);
        $photo->setFilename($filename);
        $this->photoRepository->save($photo);
    }

    /**
     * Updates an existing question photo with a new file.
     *
     * @param UploadedFile  $uploadedFile the new uploaded file instance
     * @param QuestionPhoto $photo        the question photo entity to update
     * @param Question      $question     the associated question entity
     */
    public function update(UploadedFile $uploadedFile, QuestionPhoto $photo, Question $question): void
    {
        $oldFilename = $photo->getFilename();

        if ($oldFilename) {
            $this->filesystem->remove($this->questionsTargetDirectory.'/'.$oldFilename);
        }

        $filename = $this->fileUploadService->upload($uploadedFile, $this->questionsTargetDirectory);

        $photo->setFilename($filename);
        $photo->setQuestion($question);

        $this->photoRepository->save($photo);
    }

    /**
     * Deletes the photo associated with the given question.
     *
     * @param Question $question the question entity whose photo should be deleted
     */
    public function delete(Question $question): void
    {
        $photo = $this->findOneByQuestion($question);

        if (!$photo) {
            return;
        }

        $filename = $photo->getFilename();

        if ($filename) {
            $this->filesystem->remove($this->questionsTargetDirectory.'/'.$filename);
        }

        $this->photoRepository->delete($photo);
    }
}
