<?php
$pageTitle = 'Vé CSKH';
$activePage = 'ticket';
include __DIR__ . '/../layouts/header.php';

$priorityMap = [
  'urgent' => ['Khẩn', 'background:linear-gradient(135deg,#ef4444,#dc2626)'],
  'high'   => ['Cao',  'background:linear-gradient(135deg,#f59e0b,#d97706)'],
  'normal' => ['Thường','background:linear-gradient(135deg,#3b82f6,#2563eb)'],
  'low'    => ['Thấp', 'background:linear-gradient(135deg,#64748b,#475569)'],
];
$statusMap = [
  'open'       => ['Mở', 'badge-new'],
  'processing' => ['Đang xử lý', 'badge-appointment'],
  'resolved'   => ['Đã giải quyết', 'badge-surgery'],
  'closed'     => ['Đã đóng', 'badge-contacted'],
];
?>

<div class="flex items-center justify-between mb-4">
  <div class="quick-pill"><span class="dot-live"></span><?= count($tickets) ?> vé đang xử lý</div>
  <a href="?c=ticket&a=create" class="btn btn-primary ripple">
    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tạo vé mới
  </a>
</div>

<div class="grid-3" style="grid-template-columns:repeat(auto-fill,minmax(340px,1fr));">
  <?php foreach ($tickets as $i => $t): 
    $p = $priorityMap[$t['priority']] ?? $priorityMap['normal'];
    $s = $statusMap[$t['status']] ?? $statusMap['open'];
  ?>
    <div class="card gsap-fade" style="animation-delay:<?= $i*.04 ?>s">
      <div style="padding:16px 18px;border-bottom:1px solid rgba(148,163,184,.06);display:flex;justify-content:space-between;align-items:flex-start;">
        <div>
          <div style="font-family:monospace;font-size:11px;color:#22d3ee;font-weight:600;"><?= Helper::e($t['code']) ?></div>
          <div style="font-family:'Space Grotesk';font-weight:700;color:#fff;font-size:14px;margin-top:4px;"><?= Helper::e($t['subject']) ?></div>
        </div>
        <span style="padding:3px 10px;border-radius:8px;font-size:10.5px;font-weight:700;color:#fff;<?= $p[1] ?>"><?= $p[0] ?></span>
      </div>
      <div style="padding:14px 18px;font-size:13px;color:#cbd5e1;line-height:1.5;">
        <?= Helper::e(mb_substr($t['content'] ?? '', 0, 100)) ?>...
      </div>
      <div style="padding:12px 18px;border-top:1px solid rgba(148,163,184,.06);display:flex;justify-content:space-between;align-items:center;font-size:12px;">
        <div style="display:flex;align-items:center;gap:8px;">
          <div class="ava ava-sm"><?= mb_substr($t['customer_name'], 0, 1) ?></div>
          <div>
            <div style="color:#fff;font-weight:600;"><?= Helper::e($t['customer_name']) ?></div>
            <div style="color:#64748b;font-size:11px;"><?= Helper::timeAgo($t['created_at']) ?></div>
          </div>
        </div>
        <span class="badge <?= $s[1] ?>"><span class="bdot"></span><?= $s[0] ?></span>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (empty($tickets)): ?>
    <div class="empty empty-anim" style="grid-column:1/-1;">
      <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      <p>Chưa có vé CSKH nào</p>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>