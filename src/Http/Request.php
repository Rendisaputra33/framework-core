<?php

namespace Blanks\Framework\Http;

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
}
