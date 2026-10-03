<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../core/Helper.php';
require __DIR__ . '/../core/Auth.php';
require __DIR__ . '/../core/View.php';

$c = $_GET['c'] ?? 'dashboard';
$a = $_GET['a'] ?? 'index';

$map = [
    'auth'        => 'AuthController',
    'dashboard'   => 'DashboardController',
    'customer'    => 'CustomerController',
    'pipeline'    => 'PipelineController',
    'report'      => 'ReportController',
    'appointment' => 'AppointmentController',
    'ticket'      => 'TicketController',
    'setting'     => 'SettingController',
    'khamdoan'    => 'KhamDoanController',  // ← PHẢI CÓ DÒNG NÀY
];

if (!isset($map[$c])) {
    http_response_code(404);
    echo "<h1>404 - Không tìm thấy Controller</h1>";
    echo "<p>URL: <code>?c=" . htmlspecialchars($c) . "</code></p>";
    echo "<p>Các controller có sẵn:</p><ul>";
    foreach (array_keys($map) as $key) {
        echo "<li><code>$key</code> → " . $map[$key] . "</li>";
    }
    echo "</ul>";
    die();
}

$class = $map[$c];
require __DIR__ . "/../app/controllers/$class.php";

if (!class_exists($class) || !method_exists($class, $a)) {
    http_response_code(404);
    die('404 - Action không tồn tại');
}

$controller = new $class();
$controller->$a();