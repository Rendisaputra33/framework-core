<?php

namespace Blanks\Framework\Http;

use Blanks\Framework\Application;

class Response 
{
    private int $code = 200;

    /**
     * set http response status code
     */
    public function code(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * returned view has declared
     */
    public function view(string $name, array $data = []): self
    {
        extract($data, EXTR_PREFIX_SAME, "view");
        require_once Application::$ROOT . "/views/$name.php";
        return $this;
    }

    /**
     * 
     */
    public function redirect(string $path): self
    {
        header("Location: $path", true, 301);
        return $this;
    }

    /**
     * returned json response
     */
    public function json(array $data): self
    {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8", true, $this->code);
        echo json_encode($data);
        return $this;
    }
}