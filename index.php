<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
// =============================================
// FRONT CONTROLLER - WEBBANHANG
// Đặt file này cùng cấp với thư mục app/
// =============================================

// Fix working directory để các include 'app/views/...' hoạt động đúng
chdir(__DIR__);

define('BASE_URL', '/webbanhang');
define('ROOT_PATH', __DIR__);

// Load database config
require_once ROOT_PATH . '/app/config/database.php';

// ---- Parse URL ----
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Cắt bỏ phần /webbanhang khỏi URI
if (strpos($requestUri, BASE_URL) === 0) {
    $requestUri = substr($requestUri, strlen(BASE_URL));
}

$url      = trim($requestUri, '/');
$urlParts = ($url !== '') ? explode('/', $url) : [];

// ---- Xác định Controller / Action / Params ----
$controllerName = isset($urlParts[0]) && $urlParts[0] !== '' ? $urlParts[0] : 'default';
$actionName     = isset($urlParts[1]) && $urlParts[1] !== '' ? $urlParts[1] : 'index';
$params         = array_slice($urlParts, 2);

// Thử 2 kiểu tên: giữ nguyên hoa/thường và lowercase toàn bộ
$candidates = array_unique([
    ucfirst($controllerName) . 'Controller',
    ucfirst(strtolower($controllerName)) . 'Controller',
]);

$loaded = false;

foreach ($candidates as $controllerClass) {
    $controllerFile = ROOT_PATH . '/app/controllers/' . $controllerClass . '.php';

    if (file_exists($controllerFile)) {
        require_once $controllerFile;

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            if (method_exists($controller, $actionName)) {
                call_user_func_array([$controller, $actionName], $params);
            } else {
                http_response_code(404);
                echo "<h2>404 - Action not found</h2>";
                echo "<p>Controller: <strong>{$controllerClass}</strong></p>";
                echo "<p>Action: <strong>{$actionName}</strong></p>";
            }

            $loaded = true;
            break;
        }
    }
}

if (!$loaded) {
    http_response_code(404);
    echo "<h2>404 - Controller not found</h2>";
    echo "<p>Không tìm thấy controller cho: <strong>" . htmlspecialchars($controllerName) . "</strong></p>";
    echo "<p>Đã thử tìm:</p><ul>";
    foreach ($candidates as $c) {
        echo "<li><code>app/controllers/{$c}.php</code></li>";
    }
    echo "</ul>";
}