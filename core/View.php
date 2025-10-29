<?php
class View {
    private $data = [];
    private $layout = 'main';
    
    public function __construct() {
        // Variables globales disponibles en todas las vistas
        $this->data['app_name'] = APP_NAME;
        $this->data['base_url'] = BASE_URL;
        $this->data['assets_url'] = ASSETS_URL;
        $this->data['current_user'] = Auth::user();
    }
    
    public function render($view, $data = [], $layout = null) {
        // Combinar datos
        $this->data = array_merge($this->data, $data);
        
        // Extraer variables para usar en las vistas
        extract($this->data);
        
        // Capturar contenido de la vista
        ob_start();
        
        $viewFile = APP_PATH . '/Vistas/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("Error: La vista {$view} no existe");
        }
        
        include $viewFile;
        $content = ob_get_clean();
        
        // Si no se especifica layout o es false, mostrar solo el contenido
        if ($layout === false) {
            echo $content;
            return;
        }
        
        // Usar layout especificado o el por defecto
        $layout = $layout ?: $this->layout;
        $layoutFile = APP_PATH . '/Vistas/layouts/' . $layout . '.php';
        
        if (!file_exists($layoutFile)) {
            die("Error: El layout {$layout} no existe");
        }
        
        // Incluir layout
        include $layoutFile;
    }
    
    public function setLayout($layout) {
        $this->layout = $layout;
    }
    
    public function assign($key, $value) {
        $this->data[$key] = $value;
    }
    
    public function assignArray($data) {
        $this->data = array_merge($this->data, $data);
    }
    
    // Función helper para escapar HTML
    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    // Función helper para formatear fechas
    public static function formatDate($date, $format = DATE_FORMAT) {
        if (!$date) return '';
        return date($format, strtotime($date));
    }
    
    // Función helper para formatear fecha y hora
    public static function formatDateTime($datetime, $format = DATETIME_FORMAT) {
        if (!$datetime) return '';
        return date($format, strtotime($datetime));
    }
}
?>