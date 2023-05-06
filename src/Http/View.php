<?php

namespace Blanks\Framework\Http;

class View
{
    private string $content;
    private ?string $template;

    public function __construct(
        string $content,
        ?string $template = null
    ) {
        $this->setContent($content);
        $this->setTemplate($template);
    }

    public function setContent(string $name)
    {
        $this->content = $this->trimFormat($name);
        return $this;
    }

    public function setTemplate(?string $template)
    {
        $this->template = is_null($template) 
            ? $template 
            : $this->trimFormat($template);

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    private function trimFormat(string $path)
    {
        return str_replace(".", "/", $path);
    }
}
