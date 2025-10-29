<?php
class AutenticacionControlador extends BaseControlador {
    
    public function login() {
        // Si ya está autenticado, redirigir al dashboard
        if (Auth::check()) {
            $this->redirect('dashboard');
        }
        
        $this->data['title'] = 'Iniciar Sesión - ' . APP_NAME;
        $this->render('auth/login', [], false);
    }
    
    public function authenticate() {
        if (!$this->isPost()) {
            $this->redirect('login');
        }
        
        $username = $this->getPost('username');
        $password = $this->getPost('password');
        $remember = $this->getPost('remember');
        
        // Validar campos
        if (empty($username) || empty($password)) {
            Flash::error('Por favor ingresa tu usuario y contraseña');
            $this->redirect('login');
        }
        
        // Intentar autenticación
        if (Auth::login($username, $password)) {
            // Configurar "recordarme" si está marcado
            if ($remember) {
                ini_set('session.cookie_lifetime', 86400 * 30); // 30 días
            }
            
            Flash::success('¡Bienvenido a ' . APP_NAME . '!');
            $this->redirect('dashboard');
        } else {
            Flash::error('Usuario o contraseña incorrectos');
            $this->redirect('login');
        }
    }
    
    public function logout() {
        Auth::logout();
    }
    
    public function recuperar() {
        $this->data['title'] = 'Recuperar Contraseña - ' . APP_NAME;
        $this->render('auth/recuperar', [], false);
    }
}
?>