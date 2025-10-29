<?php
abstract class BaseControlador {
    protected $view;
    protected $data = [];
    
    public function __construct() {
        $this->view = new View();
        $this->initializeData();
    }
    
    protected function initializeData() {
        $this->data = [
            'title' => APP_NAME,
            'current_user' => Auth::user(),
            'is_logged_in' => Auth::check()
        ];
    }
    
    protected function render($view, $data = [], $layout = null) {
        $this->data = array_merge($this->data, $data);
        $this->view->render($view, $this->data, $layout);
    }
    
    protected function renderJson($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url, $message = null, $type = 'info') {
        if ($message) {
            switch ($type) {
                case 'success':
                    Flash::success($message);
                    break;
                case 'error':
                    Flash::error($message);
                    break;
                case 'warning':
                    Flash::warning($message);
                    break;
                default:
                    Flash::info($message);
            }
        }
        
        Router::redirect($url);
    }
    
    protected function redirectBack($message = null, $type = 'info') {
        $referer = $_SERVER['HTTP_REFERER'] ?? 'dashboard';
        $this->redirect($referer, $message, $type);
    }
    
    protected function validate($rules, $data) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $rulesArray = explode('|', $rule);
            
            foreach ($rulesArray as $singleRule) {
                $ruleParts = explode(':', $singleRule);
                $ruleName = $ruleParts[0];
                $ruleValue = $ruleParts[1] ?? null;
                
                switch ($ruleName) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field][] = "El campo {$field} es requerido";
                        }
                        break;
                    
                    case 'email':
                        if ($value && !Util::validateEmail($value)) {
                            $errors[$field][] = "El campo {$field} debe ser un email válido";
                        }
                        break;
                    
                    case 'min':
                        if ($value && strlen($value) < $ruleValue) {
                            $errors[$field][] = "El campo {$field} debe tener al menos {$ruleValue} caracteres";
                        }
                        break;
                    
                    case 'max':
                        if ($value && strlen($value) > $ruleValue) {
                            $errors[$field][] = "El campo {$field} no puede tener más de {$ruleValue} caracteres";
                        }
                        break;
                    
                    case 'unique':
                        // Implementar validación única
                        break;
                }
            }
        }
        
        return $errors;
    }
    
    protected function getPost($key = null, $default = null) {
        return Util::getPost($key, $default);
    }
    
    protected function getGet($key = null, $default = null) {
        return Util::getGet($key, $default);
    }
    
    protected function isPost() {
        return Util::isPost();
    }
    
    protected function isGet() {
        return Util::isGet();
    }
    
    protected function requireAuth() {
        Auth::requireAuth();
    }
    
    protected function requireRole($role) {
        Auth::requireRole($role);
    }
    
    protected function requireAdmin() {
        Auth::requireAdmin();
    }
    
    protected function getPagination($page, $totalPages) {
        $pagination = [
            'current' => $page,
            'total' => $totalPages,
            'prev' => $page > 1 ? $page - 1 : null,
            'next' => $page < $totalPages ? $page + 1 : null,
            'pages' => []
        ];
        
        // Generar números de páginas
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $pagination['pages'][] = $i;
        }
        
        return $pagination;
    }
}
?>