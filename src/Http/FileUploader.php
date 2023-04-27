<?php

namespace Blanks\Framework\Http;

class FileUploader
{
    private $destinationPath;
    private $extensions;
    private $maxSize;
    private $uploadName;
    private $file;

    private function __construct(string $destination, array $file) 
    {
        $this->destinationPath = $destination;
        $this->file = $file;
    }

    public function setExtensions(array $ext): self
    {
        $this->extensions = $ext;
        return $this;
    }

    public function setMaxSize(int $max): self
    {
        $this->maxSize = $max;
        return $this;
    }

    public function save(string $dir): bool 
    {
        if (empty($files)) return false;
        $size   =   $this->file["size"];
        $name   =   md5($this->file["name"]);
        $ext    =   pathinfo($name,PATHINFO_EXTENSION);

        if (!in_array($ext, $this->extensions)) return false;
        if ($size > $this->maxSize) return false;

        $status = move_uploaded_file(
            $this->file['tmp_name'],
            "{$this->destinationPath}/$dir/$name.$ext"
        );

        $this->uploadName = "$name.$ext";
        return $status;
    }

    public function deleteUploaded()
    {
        unlink($this->destinationPath . $this->uploadName);
    }

    public static function create(string $destination, array $file): FileUploader
    {
        return new FileUploader($destination, $file);
    }
}
