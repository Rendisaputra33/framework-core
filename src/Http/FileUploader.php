<?php

namespace Blanks\Framework\Http;

class FileUploader
{
    private $destinationPath;
    private $extensions = array('jpeg', 'jpg', 'png', 'jfif', 'svg');
    private $maxSize = 10000;
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

    public function save(string $dir): bool|string
    {
        if (empty($this->file)) return false;
        $size   =   $this->file["size"];
        $name   =   $this->file["name"];
        $ext    =   pathinfo($name,PATHINFO_EXTENSION);
        $name   =   md5($name);

        if (!file_exists("{$this->destinationPath}/$dir")) {
            mkdir("{$this->destinationPath}/$dir", 0777, true);
        }

        if (!in_array($ext, $this->extensions)) return false;
        if (($size/1000) > $this->maxSize) return false;

        $status = move_uploaded_file(
            $this->file['tmp_name'],
            "{$this->destinationPath}/$dir/$name.$ext"
        );

        $this->uploadName = "/$dir/$name.$ext";
        return $status ? $this->uploadName : false;
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
