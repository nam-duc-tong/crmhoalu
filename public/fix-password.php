<?php
require __DIR__ . '/../config/database.php';

// Reset password cho tất cả users thành 123456
$hash = password_hash('123456', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ?");
$stmt->execute([$hash]);

echo "<h2>✅ Đã reset password cho TẤT CẢ users thành: <code>123456</code></h2>";
echo "<p>Hash mới: <code>$hash</code></p>";

// Hiển thị danh sách user để đăng nhập
$users = $pdo->query("SELECT id, email, full_name, role FROM users")->fetchAll();
echo "<h3>Danh sách tài khoản:</h3><table border='1' cellpadding='8'>";
echo "<tr><th>Email</th><th>Tên</th><th>Role</th><th>Password</th></tr>";
foreach ($users as $u) {
    echo "<tr><td>{$u['email']}</td><td>{$u['full_name']}</td><td>{$u['role']}</td><td>123456</td></tr>";
}
echo "</table>";
echo "<p style='color:red;font-weight:bold'>⚠️ XÓA FILE NÀY SAU KHI DÙNG XONG!</p>";