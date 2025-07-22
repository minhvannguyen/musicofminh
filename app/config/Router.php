<?php

class Router {
    public function handleRequest() {
        $url = $_GET['url'] ?? 'home/index';
        $parts = explode('/', trim($url, '/'));

        $controllerName = ucfirst($parts[0]) . 'Controller';
        $action = $parts[1] ?? 'index';
        $controllerFile = "../app/Controllers/$controllerName.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();

            if (method_exists($controller, $action)) {
                call_user_func([$controller, $action]);
            } else {
                http_response_code(404);
                echo "404 - Action không tồn tại: $action";
            }
        } else {
            http_response_code(404);
            echo "404 - Controller không tồn tại: $controllerName";
        }
    }
}
?>