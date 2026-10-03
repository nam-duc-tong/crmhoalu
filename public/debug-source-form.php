<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../core/Helper.php';
require __DIR__ . '/../core/Auth.php';

echo '<pre style="font-family:monospace;font-size:13px;line-height:1.8;background:#131b2e;color:#e2e8f0;padding:24px;">';
echo "=== DEBUG SOURCE FORM ===\n\n";

// 1. Kiểm tra $sqlsrv
echo "1️⃣ BIẾN \$sqlsrv:\n";
if (isset($sqlsrv)) {
    echo "   " . ($sqlsrv ? "✅ Có kết nối" : "❌ Null (không kết nối được)") . "\n";
} else {
    echo "   ❌ Không tồn tại biến \$sqlsrv\n";
}

// 2. Test query SQL Server
echo "\n2️⃣ QUERY SQL SERVER:\n";
if ($sqlsrv) {
    try {
        $count = $sqlsrv->query("SELECT COUNT(*) FROM DKKhamDoan")->fetchColumn();
        echo "   ✅ Tổng DKKhamDoan: $count dòng\n";

        $active = $sqlsrv->query("SELECT COUNT(*) FROM DKKhamDoan WHERE Denngay IS NULL OR Denngay >= GETDATE()")->fetchColumn();
        echo "   ✅ Đang hoạt động: $active dòng\n";

        echo "\n   📊 5 dòng đầu tiên:\n";
        $rows = $sqlsrv->query("
            SELECT TOP 5 ID, MaCty, Ten, Phanloai, Tungay, Denngay 
            FROM DKKhamDoan 
            WHERE Denngay IS NULL OR Denngay >= GETDATE()
            ORDER BY ID DESC
        ")->fetchAll();
        
        foreach ($rows as $r) {
            echo "   - ID {$r['ID']} | MaCty: {$r['MaCty']} | Phanloai: {$r['Phanloai']}\n";
            echo "     Ten: " . mb_substr($r['Ten'], 0, 60) . "\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Lỗi query: " . $e->getMessage() . "\n";
    }
}

// 3. Test nhóm theo Phanloai
echo "\n3️⃣ NHÓM THEO PHANLOAI:\n";
if ($sqlsrv) {
    try {
        $groups = $sqlsrv->query("
            SELECT Phanloai, COUNT(*) total
            FROM DKKhamDoan
            WHERE Denngay IS NULL OR Denngay >= GETDATE()
            GROUP BY Phanloai
            ORDER BY total DESC
        ")->fetchAll();
        
        if (empty($groups)) {
            echo "   ❌ KHÔNG CÓ dòng nào thỏa mãn điều kiện WHERE!\n";
            echo "   → Có thể tất cả DKKhamDoan đều có Denngay < hôm nay\n";
            echo "\n   📊 Tổng hợp không filter:\n";
            $groups2 = $sqlsrv->query("
                SELECT Phanloai, COUNT(*) total FROM DKKhamDoan GROUP BY Phanloai
            ")->fetchAll();
            foreach ($groups2 as $g) {
                echo "   - " . ($g['Phanloai'] ?: '(rỗng)') . ": {$g['total']} dòng\n";
            }
        } else {
            foreach ($groups as $g) {
                echo "   📁 " . ($g['Phanloai'] ?: 'Khác') . ": {$g['total']} dòng\n";
            }
        }
    } catch (Exception $e) {
        echo "   ❌ Lỗi: " . $e->getMessage() . "\n";
    }
}

// 4. Kiểm tra quyền
echo "\n4️⃣ QUYỀN TRUY CẬP:\n";
echo "   User: " . (Auth::check() ? Auth::user()['email'] : 'Chưa login') . "\n";
echo "   Role: " . (Auth::check() ? Auth::role() : '-') . "\n";
echo "   isManager: " . (Auth::isManager() ? 'Có' : 'Không') . "\n";

echo '</pre>';