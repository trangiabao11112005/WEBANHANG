

<?php

session_start();

require_once 'app/config/database.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/SecurityMiddleware.php';

SecurityMiddleware::handle();

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Handle root page directly without DefaultController
if (!isset($url[0]) || $url[0] === '') {
    $db = (new Database())->getConnection();
    $securityEnabled = SecurityMiddleware::isSecurityEnabled();
    $sqliResult = [];
    $sqliQuery = '';
    $sqliMessage = '';
    $xssInput = '';
    $xssSafeOutput = '';
    $xssUnsafeOutput = '';
    $showSqliResult = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $demoType = $_POST['demo_type'] ?? '';

        if ($demoType === 'sqli' && isset($_POST['username'])) {
            $username = $_POST['username'];
            $sqliQuery = $securityEnabled
                ? "SELECT * FROM account WHERE username = ?"
                : "SELECT * FROM account WHERE username = '" . $username . "'";

            try {
                $stmt = $db->prepare($sqliQuery);
                if ($securityEnabled) {
                    $stmt->execute([$username]);
                } else {
                    $stmt->execute();
                }
                $sqliResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $sqliMessage = $securityEnabled
                    ? 'The safe query was executed using prepared statements.'
                    : 'The vulnerable query was executed using string concatenation.';
                $showSqliResult = true;
            } catch (Exception $e) {
                $sqliMessage = 'Query error: ' . $e->getMessage();
            }
        }

        if ($demoType === 'xss' && isset($_POST['message'])) {
            $xssInput = $_POST['message'];
            $xssSafeOutput = htmlspecialchars($xssInput, ENT_QUOTES, 'UTF-8');

            if (!$securityEnabled) {
                $xssUnsafeOutput = $xssInput;
            }
        }
    }

    include 'app/views/home.php';
    exit;
}

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
