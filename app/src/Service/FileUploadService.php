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

use App\Contract\FileUploadServiceInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class FileUploadService.
 */
class FileUploadService implements FileUploadServiceInterface
{
    /**
     * Constructor.
     *
     * @param SluggerInterface $slugger Slugger instance
     */
    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    /**
     * Uploads a file and returns its generated unique name.
     *
     * @param UploadedFile $file            The uploaded file
     * @param string       $targetDirectory Target directory
     *
     * @return string The generated file name
     *
     * @throws FileException If the file cannot be moved to the target directory
     */
    public function upload(UploadedFile $file, string $targetDirectory): string
    {
        $originalFilename = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $safeFilename = $this->slugger->slug($originalFilename);
        $extension = $file->guessExtension();

        $fileName = $safeFilename.'-'.uniqid().'.'.$extension;

        try {
            $file->move(
                $targetDirectory,
                $fileName
            );
        } catch (FileException) {
            throw new FileException('Nie udało się zapisać pliku.');
        }

        return $fileName;
    }
}
