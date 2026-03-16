<?php

class Router {

    private $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch($method, $uri) {
        $action = $this->routes[$method][$uri] ?? null;

        if ($action === null) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        list($controllerClass, $controllerMethod) = $action;

        $controller = new $controllerClass();

        if (!method_exists($controller, $controllerMethod)) {
            http_response_code(500);
            echo 'Controller method not found';
            return;
        }

        $controller->$controllerMethod();
    }
}