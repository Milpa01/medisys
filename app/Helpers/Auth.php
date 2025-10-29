<?php
class Auth {
    
    public static function login($username, $password) {
        $db = Database::getInstance();
        
        // Buscar usuario por username o email
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE (u.username = ? OR u.email = ?) AND u.is_active = 1";
        
        $user = $db->fetch($sql, [$username, $username]);
        
        if ($user && self::verifyPassword($password, $user['password'])) {
            // Actualizar último acceso
            $db->execute("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?", [$user['id']]);
            
            // Guardar en sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_data'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'nombre' => $user['nombre'],
                'apellidos' => $user['apellidos'],
                'email' => $user['email'],
                'rol_id' => $user['rol_id'],
                'rol_nombre' => $user['rol_nombre'],
                'imagen' => $user['imagen']
            ];
            $_SESSION['logged_in'] = true;
            
            return true;
        }
        
        return false;
    }
    
    public static function logout() {
        session_unset();
        session_destroy();
        Router::redirect('login');
    }
    
    public static function check() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    public static function user() {
        return self::check() ? $_SESSION['user_data'] : null;
    }
    
    public static function id() {
        $user = self::user();
        return $user ? $user['id'] : null;
    }
    
    public static function hasRole($role) {
        $user = self::user();
        return $user ? $user['rol_nombre'] === $role : false;
    }
    
    public static function isAdmin() {
        return self::hasRole('administrador');
    }
    
    public static function isMedico() {
        return self::hasRole('medico');
    }
    
    public static function isSecretario() {
        return self::hasRole('secretario');
    }
    
    public static function hashPassword($password) {
        return hash('sha256', $password . HASH_SALT);
    }
    
    private static function verifyPassword($password, $hash) {
        return hash('sha256', $password . HASH_SALT) === $hash;
    }
    
    public static function requireAuth() {
        if (!self::check()) {
            Flash::error('Debes iniciar sesión para acceder a esta página');
            Router::redirect('login');
        }
    }
    
    public static function requireRole($role) {
        self::requireAuth();
        if (!self::hasRole($role)) {
            Flash::error('No tienes permisos para acceder a esta página');
            Router::redirect('dashboard');
        }
    }
    
    public static function requireAdmin() {
        self::requireRole('administrador');
    }
}
?>