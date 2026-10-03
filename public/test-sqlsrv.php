<?php
require __DIR__ . '/../config/database.php';

echo '<pre style="font-family:monospace;font-size:14px;line-height:1.7;background:#131b2e;color:#e2e8f0;padding:24px;">';
echo "=== TEST KẾT NỐI SQL SERVER ===\n\n";

if (!$sqlsrv) {
    echo "❌ KHÔNG kết nối được SQL Server!\n\n";
    echo "📋 Kiểm tra:\n";
    echo "  1. IP: " . SQLSRV_HOST . ":" . SQLSRV_PORT . "\n";
    echo "  2. User: " . SQLSRV_USER . "\n";
    echo "  3. Database: " . SQLSRV_DB . "\n";
    echo "  4. Driver pdo_sqlsrv đã load chưa: " . (extension_loaded('pdo_sqlsrv') ? "Có" : "Chưa") . "\n";
    echo "  5. Firewall SQL Server có mở port 1433?\n";
    echo "  6. SQL Server có bật TCP/IP?\n";
    exit;
}

echo "✅ Kết nối SQL Server thành công!\n\n";

// Test query bảng DKKhamDoan
try {
    echo "=== DỮ LIỆU BẢNG DKKhamDoan ===\n\n";
    
    $st = $sqlsrv->query("SELECT TOP 10 ngaylap, Ten FROM DKKhamDoan ORDER BY ngaylap DESC");
    $rows = $st->fetchAll();
    
    echo "Tìm thấy " . count($rows) . " bản ghi (hiển thị 10 đầu):\n\n";
    printf("%-20s | %s\n", 'ngaylap', 'Ten');
    echo str_repeat('-', 70) . "\n";
    foreach ($rows as $r) {
        printf("%-20s | %s\n", $r['ngaylap'], $r['Ten']);
    }
    
    echo "\n=== TỔNG SỐ BẢN GHI ===\n";
    $total = $sqlsrv->query("SELECT COUNT(*) FROM DKKhamDoan")->fetchColumn();
    echo "Tổng cộng: $total bản ghi\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi query: " . $e->getMessage() . "\n";
}

echo "\n=== CẤU TRÚC BẢNG DKKhamDoan ===\n\n";
try {
    $st = $sqlsrv->query("
        SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, IS_NULLABLE
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = 'DKKhamDoan'
        ORDER BY ORDINAL_POSITION
    ");
    $cols = $st->fetchAll();
    printf("%-30s | %-15s | %-10s | %s\n", 'Column', 'Type', 'Max Len', 'Nullable');
    echo str_repeat('-', 80) . "\n";
    foreach ($cols as $c) {
        printf("%-30s | %-15s | %-10s | %s\n", 
            $c['COLUMN_NAME'], 
            $c['DATA_TYPE'], 
            $c['CHARACTER_MAXIMUM_LENGTH'] ?? '-',
            $c['IS_NULLABLE']
        );
    }
} catch (PDOException $e) {
    echo "❌ Không lấy được cấu trúc: " . $e->getMessage() . "\n";
}

echo '</pre>';