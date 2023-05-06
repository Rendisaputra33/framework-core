<?php

namespace Blanks\Framework\Http;

class Router
{
    use RegisteringRoute;

    private array $routes = [];

    private string $prefix = '';

    private Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function resolve(): void
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $currentRoute = $this->getCurrentRoute($method, $path);

        if ($currentRoute === false) {
            http_response_code(404);
            echo "<h1>Not found!</h1>";
            return;
        }

        if (is_array($currentRoute['callback'])) {
            $instance = $this->createInstanceController(...$currentRoute['callback']);
            $result = call_user_func_array($instance, [$this->request, ...$currentRoute['parameters']]);
            if (is_string($result)) echo $result;
        }

        if ($currentRoute['callback'] instanceof \Closure) {
            $result = call_user_func($currentRoute['callback'], $this->request, ...$currentRoute['parameters']);
            if (is_string($result)) echo $result;
        }
    }

    private function createInstanceController(string $controller, string $method): array
    {
        $instance = new $controller();
        return [$instance, $method];
    }

    private function getCurrentRoute(string $method, string $path): array|bool
    {
        foreach ($this->routes[$method] as $item) {
            $pattern = "#^" . $item['path'] . "$#";
            if (preg_match($pattern, $path, $result)) {
                array_shift($result);
                $item['parameters'] = $result;
                return $item;
            }
        }

        return false;
    }
}
