<?php

namespace Blanks\Framework\Factory;

use Blanks\Framework\Application;

class ApplicationFactory
{
    public static function create(?string $root): Application
    {
        return Application::create($root);
    }
}
