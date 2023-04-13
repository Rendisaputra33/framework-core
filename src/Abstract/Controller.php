<?php

namespace Blanks\Framework\Abstract;

use Blanks\Framework\Application;

abstract class Controller
{
    /**
     * @param string $name
     * @param array $data
     * @return void
     */
    protected function render(string $name, array $data = []): void
    {
        require_once Application::$ROOT . "/views/$name.php";
    }

    protected function redirect(string $path): void
    {
        http_response_code(303);
        header("Location: $path");
    }
}
