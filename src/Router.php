<?php

namespace App;

class Router {
    protected array $routes = [];

    public function get(string $uri, string $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post(string $uri, string $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function direct(string $uri, string $method)
    {
        if (array_key_exists($uri, $this->routes[$method])) {
            return $this->routes[$method][$uri];
        }

        // Handle 404 Not Found
        http_response_code(404);
        require 'controllers/404.php';
        die();
    }
}
