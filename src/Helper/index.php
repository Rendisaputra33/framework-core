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

if (!function_exists('session')) {
    function session(string $name): mixed 
    {
        if (isset($_SESSION[$name])) return $_SESSION[$name];
        return null;
    }
}

if (!function_exists('setSession')) {
    function setSession(string $name, mixed $value): void 
    {
        $_SESSION[$name] = $value;
    }
}

if (!function_exists('message')) {
    function message(string $name): mixed 
    {
        if (isset($_SESSION[$name])) {
            $value = $_SESSION[$name];
            unset($_SESSION[$name]);
            return $value;
        }
        return null;
    }
}

if (!function_exists('setMessage')) {
    function setMessage(string $name, mixed $value): void 
    {
        $_SESSION[$name] = $value;
    }
}