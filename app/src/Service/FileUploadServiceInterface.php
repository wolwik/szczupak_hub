<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploadServiceInterface
{
    public function upload(UploadedFile $file): string;

    public function getTargetDirectory(): string;
}
