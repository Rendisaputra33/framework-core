<?php

use Blanks\Framework\Http\Response;

if (!function_exists('response')) {
    function response(): Response 
    {
        return new Response;
    }
}

if (!function_exists('env')) {
    function env(string $name): mixed
    {
        return $_ENV[$name];
    }
}