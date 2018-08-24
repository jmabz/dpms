<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentUploader
{
    private $targetDirectory2;

    public function __construct($targetDirectory2)
    {
        $this->targetDirectory = $targetDirectory2;
    }

    public function upload(UploadedFile $file)
    {
        foreach($this->files as $file)
        {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $file->move($this->getTargetDirectory(), $fileName);

                return $fileName;
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}