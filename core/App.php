<?php
class App {
    private $router;
    
    public function __construct() {
        $this->initializeSession();
        $this->router = new Router();
    }
    
    public function run() {
        // Obtener la URL solicitada
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        
        // Procesar la solicitud
        $this->router->dispatch($url);
    }
    
    private function initializeSession() {
        // Verificar si la sesión ya está iniciada
        if (session_status() !== PHP_SESSION_NONE) {
            return; // La sesión ya está activa
        }
        
        // Configuración de sesión ANTES de iniciarla
        ini_set('session.cookie_lifetime', SESSION_LIFETIME);
        ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_strict_mode', 1);
        
        // Iniciar la sesión
        session_start();
        
        // Regenerar ID de sesión periódicamente por seguridad
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutos
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
    
    public static function getInstance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }
}
?>