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

    /**
     * registering route with put method
     * @param string $path
     * @param Closure|array $callback
     * @return self
     */
    public function put(string $path, Closure|array $callback): self
    {
        return $this->add('PUT', $path, $callback);
    }

    /**
     * registering route with delete method
     * @param string $path
     * @param Closure|array $callback
     * @return self
     */
    public function delete(string $path, Closure|array $callback): self
    {
        return $this->add('DELETE', $path, $callback);
    }

    /**
     * Undocumented function
     *
     * @param string $prefix
     * @param Closure $callback
     * @return self
     */
    public function group(string $prefix, Closure $callback): self
    {
        $this->prefix = $prefix;
        $callback();
        $this->prefix = '';
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $method
     * @param string $path
     * @param Closure|array $callback
     * @return self
     */
    private function add(string $method, string $path, Closure|array $callback): self
    {
        $pattern = "/\{([\w\s]+)\}/";
        $concatedPath = $this->prefix . (($path == '/' && !empty($this->prefix)) ? '' : $path);
        $realPath = preg_replace($pattern, "([a-zA-Z0-9]*)", $concatedPath);
        $this->routes[$method][] = [
            'path' => $realPath,
            'callback' => $callback
        ];
        return $this;
    }
}
