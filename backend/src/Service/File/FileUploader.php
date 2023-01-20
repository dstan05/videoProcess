<?php

namespace App\Service\File;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public string $uploadPath;
    private SluggerInterface $slugger;
    public string $relativePath;
    private UploadedFile $file;
    public string $fileName;

    public function __construct(string $publicPath, string $uploadPath, SluggerInterface $slugger)
    {
        $this->uploadPath = $uploadPath;
        $this->slugger = $slugger;

        // get uploads directory relative to public path //  "/uploads/"
        $this->relativePath = self::getRelativePath($publicPath, $this->uploadPath);
    }

    public function upload(UploadedFile $file): self
    {
        $this->file = $file;
        $originalFilename = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $this->fileName = $safeFilename . '-' . uniqid() . '.' . $this->file->guessExtension();
        $file->move($this->uploadPath, $this->fileName);
        return $this;
    }

    /**
     * @param string $publicDir
     * @param string $uploadPath
     * @return string
     */
    private static function getRelativePath(string $publicDir, string $uploadPath): string
    {
        return str_replace($publicDir, '', $uploadPath);
    }
}
