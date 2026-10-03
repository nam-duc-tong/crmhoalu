<?php
// ═══════════════════════════════════════════════
// MySQL — CRM Hoa Lư (chính)
// ═══════════════════════════════════════════════
define('DB_HOST', 'localhost');
define('DB_NAME', 'hoalu_crm');
define('DB_USER', 'root');
define('DB_PASS', '');

// ═══════════════════════════════════════════════
// SQL Server — Hệ thống Phòng Khám (SHPT)
// ═══════════════════════════════════════════════
define('SQLSRV_HOST', '192.168.0.186');       // ← Đổi IP theo server của bạn
define('SQLSRV_PORT', 1433);                   // Port mặc định SQL Server
define('SQLSRV_DB',   'SHPT_PhongKham');       // Database
define('SQLSRV_USER', 'sa');                   // Username
define('SQLSRV_PASS', '123a@..');                  // ⚠️ Password của bạn
define('SQLSRV_ENABLED', true);                // Bật/tắt kết nối

// ═══════════════════════════════════════════════
define('BASE_URL', 'http://localhost:8080/CRMhoalu/public');
define('APP_NAME', 'CRM Bệnh viện Mắt Hoa Lư');
define('ROOT_PATH', dirname(__DIR__));

// ═══════════════════════════════════════════════
// Kết nối MySQL (PDO)
// ═══════════════════════════════════════════════
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("Lỗi kết nối MySQL: " . $e->getMessage());
}

// ═══════════════════════════════════════════════
// Kết nối SQL Server (PDO SQLSRV)
// ═══════════════════════════════════════════════
$sqlsrv = null;
if (SQLSRV_ENABLED && extension_loaded('pdo_sqlsrv')) {
    try {
        $sqlsrv = new PDO(
            "sqlsrv:Server=" . SQLSRV_HOST . "," . SQLSRV_PORT . ";Database=" . SQLSRV_DB . ";TrustServerCertificate=true;Encrypt=false",
            SQLSRV_USER,
            SQLSRV_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8,
            ]
        );
    } catch (PDOException $e) {
        // Không die — để CRM vẫn chạy khi mất kết nối SQL Server
        error_log("Lỗi kết nối SQL Server: " . $e->getMessage());
        $sqlsrv = null;
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}