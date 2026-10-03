<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../core/Helper.php';
require __DIR__ . '/../core/Auth.php';
require __DIR__ . '/../core/View.php';

echo '<pre style="font-family:monospace;font-size:14px;line-height:1.8;background:#131b2e;color:#e2e8f0;padding:24px;">';
echo "=== DEBUG KHÁM ĐOÀN ===\n\n";

// 1. Check files
$files = [
    'KhamDoanController' => ROOT_PATH . '/app/controllers/KhamDoanController.php',
    'PhongKhamModel'     => ROOT_PATH . '/app/models/PhongKhamModel.php',
    'View khamdoan'      => ROOT_PATH . '/app/views/khamdoan/index.php',
];
echo "1️⃣ KIỂM TRA FILE:\n";
foreach ($files as $name => $path) {
    echo "   " . (file_exists($path) ? "✅" : "❌") . " $name: $path\n";
}

// 2. Check SQL Server connection
echo "\n2️⃣ KẾT NỐI SQL SERVER:\n";
if ($sqlsrv) {
    echo "   ✅ Đã kết nối SQL Server\n";
} else {
    echo "   ❌ CHƯA kết nối SQL Server\n";
    echo "   → Kiểm tra: SQLSRV_ENABLED, host, user, pass\n";
}

// 3. Load controller
echo "\n3️⃣ LOAD CONTROLLER:\n";
$ctrlPath = ROOT_PATH . '/app/controllers/KhamDoanController.php';
if (file_exists($ctrlPath)) {
    try {
        require_once $ctrlPath;
        echo "   ✅ Đã load KhamDoanController.php\n";
        if (class_exists('KhamDoanController')) {
            echo "   ✅ Class KhamDoanController tồn tại\n";
            $methods = get_class_methods('KhamDoanController');
            echo "   📋 Methods: " . implode(', ', $methods) . "\n";
        } else {
            echo "   ❌ Class KhamDoanController KHÔNG tồn tại trong file!\n";
        }
    } catch (Throwable $e) {
        echo "   ❌ Lỗi load: " . $e->getMessage() . "\n";
        echo "   File: " . $e->getFile() . " (dòng " . $e->getLine() . ")\n";
    }
} else {
    echo "   ❌ FILE KHÔNG TỒN TẠI!\n";
    echo "   → Tạo file: app/controllers/KhamDoanController.php\n";
}

// 4. Check router map
echo "\n4️⃣ ROUTER MAP:\n";
$indexContent = file_get_contents(ROOT_PATH . '/public/index.php');
if (strpos($indexContent, "'khamdoan'") !== false) {
    echo "   ✅ Đã có 'khamdoan' trong \$map\n";
} else {
    echo "   ❌ CHƯA có 'khamdoan' trong \$map!\n";
    echo "   → Thêm dòng: 'khamdoan' => 'KhamDoanController',\n";
}

echo "\n=== KẾT THÚC ===\n";
echo '</pre>';