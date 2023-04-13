<?php

namespace Blanks\Framework\Http;

use Closure;

trait RegisteringRoute
{
    /**
     * registering route with get method
     * @param string $path
     * @param Closure|array $callback
     * @return RegisteringRoute|Router
     */
    public function get(string $path, Closure|array $callback): self
    {
        return $this->add('GET', $path, $callback);
    }

    /**
     * registering route with post method
     * @param string $path
     * @param Closure|array $callback
     * @return RegisteringRoute|Router
     */
    public function post(string $path, Closure|array $callback): self
    {
        return $this->add('POST', $path, $callback);
    }

    public function put(string $path, Closure|array $callback): self
    {
        return $this->add('PUT', $path, $callback);
    }

    public function delete(string $path, Closure|array $callback): self
    {
        return $this->add('DELETE', $path, $callback);
    }

    private function add(string $method, string $path, Closure|array $callback): self
    {
        $pattern = "/\{([\w\s]+)\}/";
        $realPath = preg_replace($pattern, "([a-zA-Z0-9]*)", $path);
        $this->routes[$method][] = [
            'path' => $realPath,
            'callback' => $callback
        ];
        return $this;
    }
}
