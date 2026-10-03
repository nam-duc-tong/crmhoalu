<?php
$pageTitle = 'Khám đoàn — Chưa kết nối';
$activePage = 'khamdoan';
include __DIR__ . '/../layouts/header.php';
?>

<div style="max-width:800px;margin:40px auto;">
  <div class="card gsap-fade">
    <div class="card-body" style="text-align:center;padding:50px 30px;">
      
      <div style="width:90px;height:90px;border-radius:24px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 12px 32px rgba(245,158,11,.4);">
        <svg viewBox="0 0 24 24" style="width:44px;height:44px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;">
          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
          <line x1="12" y1="9" x2="12" y2="13"/>
          <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
      </div>
      
      <h2 style="font-family:'Space Grotesk';font-size:24px;font-weight:700;color:#fff;margin-bottom:10px;">
        Chưa kết nối được SQL Server
      </h2>
      <p style="color:#94a3b8;font-size:14px;max-width:500px;margin:0 auto 30px;line-height:1.6;">
        Module "Khám đoàn" cần kết nối đến SQL Server <code style="color:#22d3ee;background:rgba(6,182,212,.1);padding:2px 8px;border-radius:6px;">SHPT_PhongKham</code>.
        Vui lòng kiểm tra cấu hình bên dưới.
      </p>

      <!-- CHECKLIST -->
      <div style="text-align:left;background:rgba(15,23,42,.5);border:1px solid rgba(148,163,184,.12);border-radius:14px;padding:20px;margin-bottom:24px;">
        <div style="font-size:12px;font-weight:700;color:#22d3ee;text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;">
          ✅ Checklist kiểm tra
        </div>
        
        <?php 
        $checks = [
          ['Driver pdo_sqlsrv đã cài', extension_loaded('pdo_sqlsrv')],
          ['Driver sqlsrv đã cài', extension_loaded('sqlsrv')],
          ['SQLSRV_ENABLED = true trong config', defined('SQLSRV_ENABLED') && SQLSRV_ENABLED],
          ['Host đã cấu hình', defined('SQLSRV_HOST') && SQLSRV_HOST !== ''],
        ];
        foreach ($checks as $c): ?>
          <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid rgba(148,163,184,.06);font-size:13.5px;">
            <span style="width:22px;height:22px;border-radius:50%;background:<?= $c[1] ? 'rgba(16,185,129,.15)' : 'rgba(239,68,68,.15)' ?>;display:flex;align-items:center;justify-content:center;color:<?= $c[1] ? '#34d399' : '#f87171' ?>;font-weight:700;font-size:13px;flex-shrink:0;">
              <?= $c[1] ? '✓' : '✕' ?>
            </span>
            <span style="color:<?= $c[1] ? '#e2e8f0' : '#f87171' ?>;"><?= $c[0] ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- HƯỚNG DẪN -->
      <div style="text-align:left;background:rgba(6,182,212,.05);border:1px solid rgba(6,182,212,.2);border-radius:14px;padding:20px;margin-bottom:24px;font-size:13.5px;line-height:1.8;color:#cbd5e1;">
        <div style="font-weight:700;color:#22d3ee;margin-bottom:12px;">🔧 Cách khắc phục:</div>
        
        <div style="margin-bottom:12px;">
          <b style="color:#fff;">Bước 1:</b> Tải driver PDO SQLSRV cho PHP 8.2 (Thread Safe, x64):
          <br>
          <a href="https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server" target="_blank" style="color:#22d3ee;word-break:break-all;">
            → Microsoft Drivers for PHP for SQL Server
          </a>
        </div>
        
        <div style="margin-bottom:12px;">
          <b style="color:#fff;">Bước 2:</b> Copy 2 file này vào <code style="background:rgba(15,23,42,.8);padding:2px 8px;border-radius:6px;color:#22d3ee;">C:\xampp\php\ext\</code>
          <br>
          <code style="color:#94a3b8;">php_sqlsrv_82_ts_x64.dll</code>
          <br>
          <code style="color:#94a3b8;">php_pdo_sqlsrv_82_ts_x64.dll</code>
        </div>
        
        <div style="margin-bottom:12px;">
          <b style="color:#fff;">Bước 3:</b> Mở <code style="background:rgba(15,23,42,.8);padding:2px 8px;border-radius:6px;color:#22d3ee;">C:\xampp\php\php.ini</code> thêm 2 dòng:
          <pre style="background:rgba(15,23,42,.8);padding:10px;border-radius:8px;margin-top:6px;color:#67e8f9;font-size:12px;overflow-x:auto;">extension=php_sqlsrv_82_ts_x64.dll
extension=php_pdo_sqlsrv_82_ts_x64.dll</pre>
        </div>
        
        <div style="margin-bottom:12px;">
          <b style="color:#fff;">Bước 4:</b> Cấu hình trong <code style="background:rgba(15,23,42,.8);padding:2px 8px;border-radius:6px;color:#22d3ee;">config/database.php</code>:
          <pre style="background:rgba(15,23,42,.8);padding:10px;border-radius:8px;margin-top:6px;color:#67e8f9;font-size:12px;overflow-x:auto;">define('SQLSRV_HOST', '192.168.0.186');
define('SQLSRV_PORT', 1433);
define('SQLSRV_DB',   'SHPT_PhongKham');
define('SQLSRV_USER', 'sa');
define('SQLSRV_PASS', 'your_password');
define('SQLSRV_ENABLED', true);</pre>
        </div>
        
        <div>
          <b style="color:#fff;">Bước 5:</b> Restart Apache trong XAMPP Control Panel
        </div>
      </div>

      <!-- BUTTONS -->
      <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
        <a href="test-sqlsrv.php" class="btn btn-primary" target="_blank">
          <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          Chạy Test Kết Nối
        </a>
        <a href="?c=dashboard" class="btn btn-ghost">
          <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
          Về Dashboard
        </a>
      </div>
      
      <?php if (defined('SQLSRV_HOST')): ?>
      <div style="margin-top:20px;font-size:11.5px;color:#64748b;">
        Cấu hình hiện tại: <code style="color:#94a3b8;"><?= SQLSRV_USER ?>@<?= SQLSRV_HOST ?>:<?= SQLSRV_PORT ?>/<?= SQLSRV_DB ?></code>
      </div>
      <?php endif; ?>
      
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>