<?php

namespace Blanks\Framework\Factory;

use Blanks\Framework\Http\FileUploader;

class FileUploaderFactory
{
    public static function create(array $file): FileUploader
    {
        return new FileUploader(env('FILE_STORED'), $file);
    }
}