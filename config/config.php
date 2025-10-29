<?php
// Cargar variables de entorno desde .env
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}

// Cargar .env
loadEnv(BASE_PATH . '/.env');

// Función helper para obtener variables de entorno
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

// Configuración de la aplicación
define('APP_NAME', env('APP_NAME', 'MediSys'));
define('APP_VERSION', env('APP_VERSION', '1.0.0'));
define('APP_URL', env('APP_URL', 'http://localhost/medisys'));
define('APP_ENV', env('APP_ENV', 'development'));
define('APP_DEBUG', env('APP_DEBUG', 'true') === 'true');

// Configuración de base de datos
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_NAME', env('DB_NAME', 'medisys'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));

// Configuración de seguridad
define('APP_KEY', env('APP_KEY', 'medisys_secret_key_2025'));
define('SESSION_LIFETIME', env('SESSION_LIFETIME', 1440));
define('HASH_SALT', env('HASH_SALT', 'medisys_salt_security'));

// Configuración de zona horaria
date_default_timezone_set(env('DEFAULT_TIMEZONE', 'America/Guatemala'));

// Configuración de formato de fecha
define('DATE_FORMAT', env('DATE_FORMAT', 'd/m/Y'));
define('TIME_FORMAT', env('TIME_FORMAT', 'H:i'));
define('DATETIME_FORMAT', env('DATETIME_FORMAT', 'd/m/Y H:i'));

// Configuración de paginación
define('PAGINATION_PER_PAGE', env('PAGINATION_PER_PAGE', 10));

// Rutas del sistema
define('BASE_URL', rtrim(APP_URL, '/'));
define('ASSETS_URL', BASE_URL . '/public');
define('UPLOADS_URL', BASE_URL . '/public/uploads');

// Configuración de uploads
define('UPLOAD_MAX_SIZE', env('UPLOAD_MAX_SIZE', 10485760)); // 10MB
define('UPLOAD_PATH', env('UPLOAD_PATH', 'public/uploads/'));
?>