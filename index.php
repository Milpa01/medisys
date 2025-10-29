<?php
// Configuración de errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// NO iniciar sesión aquí, se hace en App.php

// Definir constantes del sistema
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('CORE_PATH', BASE_PATH . '/core');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Cargar configuración
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';

// Cargar clases del core
require_once CORE_PATH . '/Database.php';
require_once CORE_PATH . '/Router.php';
require_once CORE_PATH . '/View.php';
require_once CORE_PATH . '/App.php';

// Cargar helpers
require_once APP_PATH . '/Helpers/Auth.php';
require_once APP_PATH . '/Helpers/Session.php';
require_once APP_PATH . '/Helpers/Flash.php';
require_once APP_PATH . '/Helpers/Util.php';

// Cargar modelo base
require_once APP_PATH . '/Modelos/BaseModelo.php';

// Cargar controlador base
require_once APP_PATH . '/Controladores/BaseControlador.php';

// Inicializar aplicación
$app = new App();
$app->run();
?>