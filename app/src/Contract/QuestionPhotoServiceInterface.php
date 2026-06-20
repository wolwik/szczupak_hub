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

use App\Entity\QuestionPhoto;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface QuestionPhotoServiceInterface.
 */
interface QuestionPhotoServiceInterface
{
    /**
     * Finds a single photo associated with the given question.
     *
     * @param Question $question the question entity
     *
     * @return QuestionPhoto|null the question photo entity, or null if not found
     */
    public function findOneByQuestion(Question $question): ?QuestionPhoto;

    /**
     * Creates and saves a new question photo.
     *
     * @param UploadedFile  $uploadedFile the uploaded file instance
     * @param QuestionPhoto $photo        the question photo entity to populate
     * @param Question      $question     the associated question entity
     */
    public function create(UploadedFile $uploadedFile, QuestionPhoto $photo, Question $question): void;

    /**
     * Updates an existing question photo with a new file.
     *
     * @param UploadedFile  $uploadedFile the new uploaded file instance
     * @param QuestionPhoto $photo        the question photo entity to update
     * @param Question      $question     the associated question entity
     */
    public function update(UploadedFile $uploadedFile, QuestionPhoto $photo, Question $question): void;

    /**
     * Deletes the photo associated with the given question.
     *
     * @param Question $question the question entity whose photo should be deleted
     */
    public function delete(Question $question): void;
}
