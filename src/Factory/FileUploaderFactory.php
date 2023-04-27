<?php

namespace Blanks\Framework\Factory;

use Blanks\Framework\Application;
use Blanks\Framework\Http\FileUploader;

class FileUploaderFactory
{
    public static function create(array $file): FileUploader
    {
        $path = Application::$ROOT . env('FILE_STORED'); 
        return FileUploader::create($path, $file);
    }
}