<?php
class Session {
    
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    public static function clear() {
        session_unset();
    }
    
    public static function destroy() {
        session_destroy();
    }
    
    public static function flash($key, $value = null) {
        if ($value === null) {
            // Obtener y eliminar
            $flashValue = self::get('flash_' . $key);
            self::remove('flash_' . $key);
            return $flashValue;
        } else {
            // Establecer
            self::set('flash_' . $key, $value);
        }
    }
    
    public static function hasFlash($key) {
        return self::has('flash_' . $key);
    }
    
    public static function getFlash($key, $default = null) {
        $value = self::get('flash_' . $key, $default);
        self::remove('flash_' . $key);
        return $value;
    }
    
    public static function regenerateId() {
        session_regenerate_id(true);
    }
    
    public static function getId() {
        return session_id();
    }
}
?>