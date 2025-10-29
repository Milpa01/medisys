<?php
class Router {
    private $routes;
    
    public function __construct() {
        $this->routes = include CONFIG_PATH . '/routes.php';
    }
    
    public function dispatch($url = '') {
        // Limpiar URL
        $url = trim($url, '/');
        
        // Buscar la ruta exacta
        if (isset($this->routes[$url])) {
            $route = $this->routes[$url];
            return $this->callController($route['controller'], $route['method']);
        }
        
        // Buscar rutas con parámetros
        foreach ($this->routes as $pattern => $route) {
            if ($this->matchRoute($pattern, $url)) {
                return $this->callController($route['controller'], $route['method']);
            }
        }
        
        // Si no se encuentra la ruta, mostrar error 404
        $this->show404();
    }
    
    private function matchRoute($pattern, $url) {
        // Convertir patrón a expresión regular
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        return preg_match($pattern, $url);
    }
    
    private function callController($controllerName, $method) {
        $controllerFile = APP_PATH . '/Controladores/' . $controllerName . '.php';
        
        if (!file_exists($controllerFile)) {
            die("Error: El controlador {$controllerName} no existe");
        }
        
        require_once $controllerFile;
        
        if (!class_exists($controllerName)) {
            die("Error: La clase {$controllerName} no existe");
        }
        
        $controller = new $controllerName();
        
        if (!method_exists($controller, $method)) {
            die("Error: El método {$method} no existe en {$controllerName}");
        }
        
        return $controller->$method();
    }
    
    private function show404() {
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<p>La página que buscas no existe.</p>";
        echo "<a href='" . BASE_URL . "'>Volver al inicio</a>";
        exit;
    }
    
    public static function url($path = '') {
        return BASE_URL . '/' . ltrim($path, '/');
    }
    
    public static function redirect($path = '') {
        header('Location: ' . self::url($path));
        exit;
    }
}
?>