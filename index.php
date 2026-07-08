<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/schema.php';
require_once __DIR__ . '/config/autoload.php';

$db = Database::getInstance();

$schema = new Schema();
$schema->install();

App::loadSettings();

date_default_timezone_set(App::getSetting('shop_timezone', 'Africa/Nairobi'));

$auth = new Auth();
$auth->checkRememberMe('admin');
$auth->checkRememberMe('customer');

function route_method($name) {
    $name = preg_replace('/[^a-zA-Z0-9]+/', ' ', $name);
    $name = str_replace(' ', '', ucwords($name));
    return lcfirst($name);
}

$url = $_GET['url'] ?? '';
if (empty($url) && isset($_SERVER['REQUEST_URI'])) {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    $basePath = str_replace('\\', '/', $basePath);
    if ($basePath !== '/' && strpos($path, $basePath) === 0) {
        $path = substr($path, strlen($basePath));
    }
    $url = trim($path, '/');
}
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

if ($urlParts[0] === 'admin' && !empty($urlParts[1])) {
    $adminSub = 'Admin' . ucfirst($urlParts[1]) . 'Controller';
    $adminFile = __DIR__ . '/controllers/' . $adminSub . '.php';
    if (file_exists($adminFile)) {
        $controllerName = $adminSub;
        $methodName = route_method($urlParts[2] ?? 'index');
        $params = array_slice($urlParts, 3);
        $controllerFile = $adminFile;
    } else {
        $controllerName = 'AdminController';
        $methodName = route_method($urlParts[1]);
        $params = array_slice($urlParts, 2);
        $controllerFile = __DIR__ . '/controllers/AdminController.php';
    }
} elseif ($urlParts[0] === 'api' && !empty($urlParts[1])) {
    $controllerName = 'ApiController';
    $methodName = route_method(implode('-', array_slice($urlParts, 1)));
    $params = [];
    $controllerFile = __DIR__ . '/controllers/ApiController.php';
} elseif ($urlParts[0] === 'admin') {
    $controllerName = 'AdminController';
    $methodName = route_method($urlParts[1] ?? 'dashboard');
    $params = array_slice($urlParts, 2);
    $controllerFile = __DIR__ . '/controllers/AdminController.php';
} else {
    $first = $urlParts[0] ?? '';
    $authRoutes = ['login', 'register', 'logout', 'forgot-password', 'reset-password'];
    if (in_array($first, $authRoutes)) {
        $controllerName = 'AuthController';
        $methodName = route_method($first);
        $params = array_slice($urlParts, 1);
        $controllerFile = __DIR__ . '/controllers/AuthController.php';
    } elseif (!empty($first)) {
        $genController = ucfirst($first) . 'Controller';
        $genFile = __DIR__ . '/controllers/' . $genController . '.php';
        if (file_exists($genFile)) {
            $controllerName = $genController;
            $methodName = route_method($urlParts[1] ?? 'index');
            $params = array_slice($urlParts, 2);
            $controllerFile = $genFile;
        } else {
            $controllerName = 'HomeController';
            $methodName = route_method($first);
            $params = array_slice($urlParts, 1);
            $controllerFile = __DIR__ . '/controllers/HomeController.php';
        }
    } else {
        $controllerName = 'HomeController';
        $methodName = 'index';
        $params = [];
        $controllerFile = __DIR__ . '/controllers/HomeController.php';
    }
}

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $methodName)) {
            call_user_func_array([$controller, $methodName], $params);
        } else {
            http_response_code(404);
            echo "Method '{$methodName}' not found in {$controllerName}";
        }
    } else {
        http_response_code(404);
        echo "Controller class '{$controllerName}' not found";
    }
} else {
    http_response_code(404);
    echo "Controller file '{$controllerFile}' not found";
}
