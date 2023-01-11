<?php
//echo bin2hex(random_bytes(16));
//die();
/**
 * Front controller
 *
 * PHP version 7.0
 */
///**
// * Composer
// */
require dirname(__DIR__) . '/vendor/autoload.php';
///**
// * Error and Exception handling
// */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');
// Start session
session_start();
/**
 * Routing
 */
$router = new Core\Router();
// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('{controller}/{action}');
try {
	$router->dispatch($_SERVER['QUERY_STRING']);
} catch (Exception $e) {
	echo $e->getMessage();
}
