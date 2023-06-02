<?php

namespace Blanks\Framework\Factory;

use Blanks\Framework\Application;

class ApplicationFactory
{
    public static function create(?string $root): Application
    {
        session_start();
        return Application::create($root);
    }
}
