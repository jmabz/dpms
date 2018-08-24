<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentUploader
{
    private $targetDirectory2;

    public function __construct($targetDirectory2)
    {
        $this->targetDirectory2 = $targetDirectory2;
    }

    public function upload(UploadedFile $files)
    {
        foreach ($files as $file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $file->move($this->getTargetDirectory2(), $fileName);

                return $fileName;
        }
    }

    public function getTargetDirectory2()
    {
        return $this->targetDirectory2;
    }
}