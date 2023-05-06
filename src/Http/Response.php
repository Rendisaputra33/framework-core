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
    public function view(View $view, array $data = []): string
    {
        extract($data, EXTR_PREFIX_SAME, "model");
        // get content
        ob_start();
        require_once Application::$ROOT . "/views/{$view->getContent()}.php";
        $content = ob_get_clean();
        // get template

        if (!is_null($view->getTemplate())) {
            ob_start();
            require_once Application::$ROOT . "/views/template/{$view->getTemplate()}.php";
            $template = ob_get_clean();
            return str_replace('{{content}}', $content, $template);
        }

        return $content;
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
    public function json(array $data): string
    {
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8", true, $this->code);
        return json_encode($data);
    }
}