<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../config/database.php';

$hash = password_hash('123456', PASSWORD_DEFAULT);

// ==== TẠO / CẬP NHẬT ADMIN ====
$st = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$st->execute(['admin@hoalu.vn']);
$u = $st->fetch();

if ($u) {
    $pdo->prepare("UPDATE users SET password = ?, role = 'admin', is_active = 1 WHERE id = ?")
        ->execute([$hash, $u['id']]);
} else {
    $pdo->prepare("INSERT INTO users (full_name, email, password, role, team, kpi_target) VALUES (?,?,?,?,?,?)")
        ->execute(['Nguyễn Văn Admin', 'admin@hoalu.vn', $hash, 'admin', 'BOD', 0]);
}

// ==== THÊM USERS DEMO ====
$demoUsers = [
    ['Trần Quản Lý',    'manager@hoalu.vn', 'manager', 'Sales A', 500000000],
    ['Nguyễn Thị Lan',  'lan@hoalu.vn',     'sales',   'Sales A', 200000000],
    ['Lê Văn Hùng',     'hung@hoalu.vn',    'sales',   'Sales B', 200000000],
    ['Phạm Thu Hà',     'ha@hoalu.vn',      'cskh',    'CSKH',    0],
];

foreach ($demoUsers as $du) {
    $st = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $st->execute([$du[1]]);
    if ($st->fetch()) {
        $pdo->prepare("UPDATE users SET password = ?, role = ?, team = ?, kpi_target = ?, is_active = 1 WHERE email = ?")
            ->execute([$hash, $du[2], $du[3], $du[4], $du[1]]);
    } else {
        $pdo->prepare("INSERT INTO users (full_name, email, password, role, team, kpi_target) VALUES (?,?,?,?,?,?)")
            ->execute([$du[0], $du[1], $hash, $du[2], $du[3], $du[4]]);
    }
}

// ==== HIỂN THỊ KẾT QUẢ ====
echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Setup</title>';
echo '<style>
body{font-family:Inter,system-ui,sans-serif;background:#050816;color:#e2e8f0;padding:40px;max-width:800px;margin:0 auto;line-height:1.6;}
h1{color:#22d3ee;font-family:Space Grotesk,sans-serif;}
code{background:#0f172a;padding:3px 9px;border-radius:6px;color:#22d3ee;font-family:ui-monospace,monospace;}
table{width:100%;border-collapse:collapse;margin-top:20px;background:rgba(15,23,42,.6);border-radius:12px;overflow:hidden;}
th,td{padding:14px 18px;text-align:left;border-bottom:1px solid rgba(148,163,184,.12);}
th{color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:.08em;}
.warn{background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);padding:16px;border-radius:12px;color:#fca5a5;margin-top:24px;font-weight:600;}
a{color:#22d3ee;text-decoration:none;font-weight:600;}
.btn{display:inline-block;margin-top:20px;padding:14px 28px;background:linear-gradient(135deg,#06b6d4,#3b82f6);color:#fff;border-radius:12px;font-weight:700;}
</style></head><body>';

echo '<h1>✅ Setup hoàn tất!</h1>';
echo '<p>Tất cả tài khoản dưới đây đã có mật khẩu: <code>123456</code></p>';

echo '<table><tr><th>Email</th><th>Vai trò</th><th>Mật khẩu</th></tr>';
$all = $pdo->query("SELECT email, role FROM users ORDER BY id")->fetchAll();
foreach ($all as $u) {
    echo "<tr><td><code>{$u['email']}</code></td><td>{$u['role']}</td><td>123456</td></tr>";
}
echo '</table>';

echo '<div class="warn">⚠️ XÓA FILE NÀY NGAY sau khi đăng nhập thành công! (public/setup.php)</div>';
echo '<a class="btn" href="index.php?c=auth&a=login">→ Đi đến trang đăng nhập</a>';
echo '</body></html>';