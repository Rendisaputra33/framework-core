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
        header("Location: $path", true, 301);
        exit();
    }

    protected function json(int $code = 200, array $data)
    {
        // remove any string that could create an invalid JSON 
        // such as PHP Notice, Warning, logs...
        ob_clean();

        // this will clean up any previously added headers, to start clean
        header_remove();

        // Set the content type to JSON and charset 
        // (charset can be set to something else)
        header("Content-type: application/json; charset=utf-8", true, $code);
        echo json_encode($data);
    }
}
