<?php
// Variables que definen el nombre actual del hosting
//$myhost = "http://music-app-php.infinityfreeapp.com";// Cambia esto a tu URL local o de producción
$myhost = "http://localhost";
$myproject = "music-app"; // Solo necesitas cambiar este valor si renombras la carpeta

// Construir la ruta completa
$mysite = $myhost . "/" . $myproject;
date_default_timezone_set('America/Tegucigalpa');

// Variables estáticas que definen las rutas absolutas del proyecto
define('__ROOT__', $_SERVER["DOCUMENT_ROOT"]);
define('__SITE_PATH', $mysite);
define('__CLS_PATH', __ROOT__ . "/" . $myproject . "/app_core/classes/");
define('__CTR_PATH', __ROOT__ . "/" . $myproject . "/app_core/controllers/");
define('__VWS_PATH', __ROOT__ . "/" . $myproject . "/app_core/views/");
define('__VWS_HOST_PATH', $mysite . "/app_core/views/");
define('__CTR_HOST_PATH', $mysite . "/app_core/controllers/");

define('__RSC_HOST_PATH', $mysite . "/app_core/resources/"); 
define('__RSC_PHO_HOST_PATH', $mysite . "/app_core/resources/photos/"); 


define('__JS_PATH', $mysite . "/app_design/js/");
define('__CSS_PATH', $mysite . "/app_design/css/");
define('__IMG_PATH', $mysite . "/app_design/img/");

// GLOBAL FUNCTIONS
set_error_handler("my_error_handler", E_ALL);

// Incluir clase de mensajes
require_once(__CLS_PATH . "cls_message.php");

// Para almacenar mensajes de error y advertencia en una pila
$_SESSION['error_messages'] = $_SESSION['error_messages'] ?? [];

// Maneja globalmente los warnings y excepciones de PHP
function my_error_handler(int $errno, string $errstr, string $errfile, int $errline): bool
{
    global $_SESSION;
    
    // Agregar el error a la pila de mensajes
    $_SESSION['error_messages'][] = [
        'message' => $errstr,
        'type' => 'error',
        'file' => $errfile,
        'line' => $errline
    ];
    
    // Indica que el error ha sido manejado
    return true;
}

// Función para mostrar todos los errores acumulados
function display_all_errors(): void
{
    global $_SESSION;
    
    if (!empty($_SESSION['error_messages'])) {
        echo '<div class="error-stack">';
        foreach ($_SESSION['error_messages'] as $error) {
            cls_Message::show_message($error['message'], $error['type'], "");
        }
        echo '</div>';
        
        // Limpiar la pila de mensajes después de mostrarlos
        $_SESSION['error_messages'] = [];
    }
}
?>