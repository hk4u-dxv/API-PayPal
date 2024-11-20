<?php
// Carga las variables de entorno
require_once __DIR__ . '/../includes/Environment.php';
Environment::load();

// Configuración de PayPal
define('PAYPAL_CLIENT_ID', Environment::get('PAYPAL_CLIENT_ID'));
define('PAYPAL_MODE', Environment::get('PAYPAL_MODE', 'sandbox'));
define('PAYPAL_CURRENCY', Environment::get('PAYPAL_CURRENCY', 'MXN'));

// Configuración de la aplicación
define('APP_DEBUG', Environment::get('APP_DEBUG', false));

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', Environment::get('APP_DEBUG', false)); 