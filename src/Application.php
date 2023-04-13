<?php

namespace Blanks\Framework;

use Blanks\Framework\Http\Request;
use Blanks\Framework\Http\Router;

class Application extends Router
{
    public static string $ROOT;

    private function __construct(string $rootApp)
    {
        self::$ROOT = $rootApp;
        parent::__construct(new Request());
    }

    public function run(): void
    {
        $this->resolve();
    }

    public static function create(string $root): self
    {
        return new Application($root);
    }
}
