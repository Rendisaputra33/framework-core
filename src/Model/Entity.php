<?php

namespace Blanks\Framework\Model;

abstract class Entity
{
    abstract public function mapping(): array;

    public function map(array $data): void
    {
        foreach ($this->mapping() as $key => $value) {
            $this->{$value} = $data[$key] ?? null;
        }
    }
}
