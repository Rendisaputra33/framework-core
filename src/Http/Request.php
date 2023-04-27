<?php

namespace Blanks\Framework\Http;

use Blanks\Framework\Factory\FileUploaderFactory;

class Request
{
    public function getPath(): string
    {
        return $_SERVER['PATH_INFO'] ?? '/';
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getBody(): array
    {
        $body = [];
        foreach ($_POST as $key => $value) {
            $body[$key] = $value;
        }
        return $body;
    }

    public function getQuery(): array
    {
        $body = [];
        foreach ($_GET as $key => $value) {
            $body[$key] = $value;
        }
        return $body;
    }

    public function toBodyEntity(string $classes): mixed
    {
        $body = $this->getBody();
        $object = new $classes();
        $object->map($body);
        return $object;
    }

    public function hasFile()
    {
        return !empty($_FILES);
    }

    /**
     * @return FileUploader[] 
     */
    public function file(string $name): array
    {
        if ($this->hasFile()) return [];
        if (!isset($_FILES[$name])) return [];
        if(!is_array($_FILES[$name]['name'])) return [FileUploaderFactory::create($_FILES[$name])];
        return $this->mapFile($_FILES[$name]);
    }

    public function mapFile(array $files): array
    {
        $mapped = [];
        foreach ($files as $key => $value) {
            foreach ($value as $index => $value) {
                $mapped[$index][$key] = $value;
            }
        }
        return array_map(fn($it) => FileUploaderFactory::create($it), $mapped);
    }
}
