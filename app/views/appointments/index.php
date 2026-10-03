<?php
$pageTitle = 'Lịch hẹn';
$activePage = 'appointment';
include __DIR__ . '/../layouts/header.php';

$statusMap = [
  'pending'   => ['Chờ xác nhận', 'badge-appointment'],
  'confirmed' => ['Đã xác nhận', 'badge-contacted'],
  'done'      => ['Đã khám', 'badge-surgery'],
  'cancelled' => ['Đã hủy', 'badge-lost'],
  'no_show'   => ['Không đến', 'badge-lost'],
];
?>

<div class="stats mb-5">
  <div class="stat c-cyan gsap-fade">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
    <div class="stat-val" data-count="<?= count($apts) ?>">0</div>
    <div class="stat-lbl">Tổng lịch hẹn</div>
  </div>
  <div class="stat c-amber gsap-fade">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    <div class="stat-val" data-count="<?= count($upcoming) ?>">0</div>
    <div class="stat-lbl">Sắp tới</div>
  </div>
  <div class="stat c-emerald gsap-fade">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
    <div class="stat-val" data-count="<?= count(array_filter($apts, fn($a) => $a['status'] === 'done')) ?>">0</div>
    <div class="stat-lbl">Đã khám</div>
  </div>
</div>

<div class="grid-2">
  <div class="card gsap-fade">
    <div class="card-head">
      <h3><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Sắp tới</h3>
      <a href="?c=appointment&a=create" class="btn btn-primary btn-sm ripple">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Đặt lịch
      </a>
    </div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
      <?php if (empty($upcoming)): ?>
        <div class="empty empty-anim">
          <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <p>Chưa có lịch hẹn sắp tới</p>
        </div>
      <?php else: foreach ($upcoming as $i => $a): 
        $ts = strtotime($a['scheduled_at']);
      ?>
        <div class="apt-card gsap-slide-left" style="animation-delay:<?= $i*.05 ?>s">
          <div class="apt-date">
            <div class="day"><?= date('d', $ts) ?></div>
            <div class="month">Th<?= date('m', $ts) ?></div>
          </div>
          <div style="flex:1;min-width:0;">
            <div style="font-weight:600;color:#fff;font-size:14px;"><?= Helper::e($a['customer_name']) ?></div>
            <div style="font-size:12px;color:#94a3b8;margin-top:2px;"><?= Helper::e($a['title'] ?: 'Khám bệnh') ?></div>
            <div style="font-size:11.5px;color:#64748b;margin-top:4px;display:flex;gap:10px;">
              <span>⏰ <?= date('H:i', $ts) ?></span>
              <span>📞 <?= Helper::e($a['customer_phone']) ?></span>
            </div>
          </div>
          <div>
            <span class="badge <?= $statusMap[$a['status']][1] ?>">
              <span class="bdot"></span><?= $statusMap[$a['status']][0] ?>
            </span>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>

  <div class="card gsap-fade">
    <div class="card-head">
      <h3><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18M15 3v18"/></svg>Tất cả lịch hẹn</h3>
    </div>
    <div style="max-height:600px;overflow-y:auto;">
      <?php foreach ($apts as $a): 
        $ts = strtotime($a['scheduled_at']);
        $isPast = $ts < time();
      ?>
        <div style="padding:14px 20px;border-bottom:1px solid rgba(148,163,184,.05);display:flex;gap:12px;align-items:center;opacity:<?= $isPast ? '.6' : '1' ?>;">
          <div class="ava ava-sm"><?= mb_substr($a['customer_name'], 0, 1) ?></div>
          <div style="flex:1;min-width:0;">
            <div style="font-weight:600;color:#fff;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= Helper::e($a['customer_name']) ?></div>
            <div style="font-size:11.5px;color:#64748b;margin-top:2px;"><?= date('d/m/Y H:i', $ts) ?> • <?= Helper::e($a['title'] ?: 'Khám') ?></div>
          </div>
          <span class="badge <?= $statusMap[$a['status']][1] ?>"><?= $statusMap[$a['status']][0] ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>