<?php

namespace Blanks\Framework\Http;

class View
{
    public function __construct(
        private string $content,
        private string $template
    ) {}

    public function setContent(string $name)
    {
        $this->content = $name;
        return $this;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
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
}
