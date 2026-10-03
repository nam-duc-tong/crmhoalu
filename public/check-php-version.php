<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Check PHP</title></head>
<body style="font-family:monospace;background:#131b2e;color:#e2e8f0;padding:40px;line-height:1.8;">

<h2 style="color:#22d3ee;">🔍 PHP đang chạy website của bạn</h2>

<table style="border-collapse:collapse;font-size:14px;">
  <tr><td style="padding:8px 20px 8px 0;color:#94a3b8;">PHP Version</td>
      <td style="padding:8px 0;color:#fff;font-weight:700;"><?= PHP_VERSION ?></td></tr>
  
  <tr><td style="padding:8px 20px 8px 0;color:#94a3b8;">Kiến trúc</td>
      <td style="padding:8px 0;color:#fff;font-weight:700;"><?= PHP_INT_SIZE === 8 ? 'x64 (64-bit)' : 'x86 (32-bit)' ?></td></tr>
  
  <tr><td style="padding:8px 20px 8px 0;color:#94a3b8;">Thread Safety</td>
      <td style="padding:8px 0;color:#fff;font-weight:700;"><?= ZEND_THREAD_SAFE ? 'TS (Thread Safe)' : 'NTS (Non-Thread Safe)' ?></td></tr>
  
  <tr><td style="padding:8px 20px 8px 0;color:#94a3b8;">Compiler</td>
      <td style="padding:8px 0;color:#fff;font-weight:700;"><?= PHP_COMPILER ?: 'N/A' ?></td></tr>
  
  <tr><td style="padding:8px 20px 8px 0;color:#94a3b8;">Loaded php.ini</td>
      <td style="padding:8px 0;color:#22d3ee;font-weight:700;"><?= php_ini_loaded_file() ?></td></tr>
  
  <tr><td style="padding:8px 20px 8px 0;color:#94a3b8;">Extension dir</td>
      <td style="padding:8px 0;color:#22d3ee;font-weight:700;"><?= ini_get('extension_dir') ?></td></tr>
</table>

<h2 style="color:#22d3ee;margin-top:30px;">📥 Cần tải driver SQLSRV cho:</h2>
<div style="background:rgba(6,182,212,.1);border:1px solid rgba(6,182,212,.3);padding:16px;border-radius:12px;font-size:15px;">
  <b>PHP <?= PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION ?></b>
  ·
  <b><?= ZEND_THREAD_SAFE ? 'Thread Safe' : 'Non-Thread Safe' ?></b>
  ·
  <b><?= PHP_INT_SIZE === 8 ? 'x64' : 'x86' ?></b>
</div>

<h2 style="color:#22d3ee;margin-top:30px;">🔗 Link tải driver</h2>
<a href="https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server"
   style="color:#22d3ee;font-size:15px;" target="_blank">
   → Microsoft Drivers for PHP for SQL Server
</a>

<h2 style="color:#22d3ee;margin-top:30px;">📝 Các bước tiếp theo</h2>
<pre style="background:#1a2332;padding:20px;border-radius:12px;color:#cbd5e1;font-size:13px;overflow-x:auto;">
1. Tải driver SQLSRV cho PHP <?= PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION ?> <?= ZEND_THREAD_SAFE ? 'Thread Safe' : 'NTS' ?> x64
2. Giải nén, copy 2 file .dll vào: <?= ini_get('extension_dir') ?>
3. Mở file php.ini: <?= php_ini_loaded_file() ?>
4. Thêm 2 dòng cuối file:
   extension=php_sqlsrv_<?= PHP_MAJOR_VERSION . PHP_MINOR_VERSION ?>_<?= ZEND_THREAD_SAFE ? 'ts' : 'nts' ?>_x64.dll
   extension=php_pdo_sqlsrv_<?= PHP_MAJOR_VERSION . PHP_MINOR_VERSION ?>_<?= ZEND_THREAD_SAFE ? 'ts' : 'nts' ?>_x64.dll
5. STOP rồi START Apache (không phải Restart)
6. Reload trang test-sqlsrv.php
</pre>

</body></html>