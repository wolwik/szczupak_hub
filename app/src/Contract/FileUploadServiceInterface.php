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

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface FileUploadServiceInterface.
 */
interface FileUploadServiceInterface
{
    /**
     * Uploads a file.
     *
     * @param UploadedFile $file            The uploaded file instance
     * @param string       $targetDirectory Target directory
     *
     * @return string The filename of the uploaded file
     */
    public function upload(UploadedFile $file, string $targetDirectory): string;
}
