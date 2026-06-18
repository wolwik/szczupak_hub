<?php

namespace App\Contract;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploadServiceInterface
{
    public function upload(UploadedFile $file): string;

    public function getTargetDirectory(): string;
}
