<?php

namespace Blanks\Framework\Abstract;

abstract class Collection
{

    public function __construct(private array $data)
    {
    }


    public function __get($name): mixed
    {
        if (array_key_exists($name, $this->data)) return $this->data[$name];
        return null;
    }
}
