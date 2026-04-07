

<?php

session_start();

require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/SecurityMiddleware.php';

SecurityMiddleware::handle();

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Controller
$controllerName = isset($url[0]) && $url[0] != ''
    ? ucfirst($url[0]) . 'Controller'
    : 'DefaultController';

// Action
$action = isset($url[1]) && $url[1] != ''
    ? $url[1]
    : 'index';

// Check controller
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    die('Controller không tồn tại');
}

require_once 'app/controllers/' . $controllerName . '.php';

$controller = new $controllerName();

// Check action
if (!method_exists($controller, $action)) {
    die('Hành động không tồn tại');
}

// Call action
call_user_func_array([$controller, $action], array_slice($url, 2));
