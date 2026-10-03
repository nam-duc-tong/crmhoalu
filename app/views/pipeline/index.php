<?php
$pageTitle = 'Pipeline';
$activePage = 'pipeline';
include __DIR__ . '/../layouts/header.php';

$stageMeta = [
  'new'         => ['Mới', 'h-new'],
  'contacted'   => ['Đã liên hệ', 'h-contacted'],
  'appointment' => ['Hẹn khám', 'h-appointment'],
  'consulted'   => ['Đã tư vấn', 'h-consulted'],
  'surgery'     => ['Đã phẫu thuật', 'h-surgery'],
];
?>

<p style="color:#94a3b8;font-size:13px;margin-bottom:18px;">Kéo thả để di chuyển khách hàng giữa các giai đoạn</p>

<div class="pipeline">
  <?php foreach ($stages as $key): 
    $items = $pipeline[$key] ?? [];
    $meta = $stageMeta[$key];
  ?>
    <div class="pipe-col">
      <div class="pipe-head <?= $meta[1] ?>">
        <span><?= $meta[0] ?></span>
        <span class="pcount"><?= count($items) ?></span>
      </div>
      <div class="pipe-body" data-status="<?= $key ?>">
        <?php foreach ($items as $c): ?>
          <div class="pipe-card" data-id="<?= $c['id'] ?>">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
              <div style="display:flex;align-items:center;gap:9px;min-width:0;">
                <div class="ava ava-sm"><?= mb_substr($c['full_name'], 0, 1) ?></div>
                <div style="min-width:0;">
                  <div style="font-weight:600;color:#fff;font-size:13px;line-height:1.2;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= Helper::e($c['full_name']) ?></div>
                  <div class="chip" style="margin-top:4px;"><?= Helper::e($c['code']) ?></div>
                </div>
              </div>
              <a href="?c=customer&a=show&id=<?= $c['id'] ?>" style="color:#64748b;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
              </a>
            </div>
            <div style="font-size:11.5px;color:#94a3b8;display:flex;align-items:center;gap:6px;margin-bottom:4px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              <?= Helper::e($c['phone']) ?>
            </div>
            <?php if ($c['interest']): ?>
            <div style="font-size:11.5px;color:#94a3b8;display:flex;align-items:center;gap:6px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <?= Helper::e($c['interest']) ?>
            </div>
            <?php endif; ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:10px;margin-top:10px;border-top:1px solid rgba(148,163,184,.08);">
              <div style="font-family:'Space Grotesk';font-size:13px;font-weight:700;color:#34d399;"><?= Helper::money($c['estimated_value']) ?></div>
              <?php if ($c['assigned_name']): ?>
                <div class="ava ava-sm" title="<?= Helper::e($c['assigned_name']) ?>"><?= mb_substr($c['assigned_name'], 0, 1) ?></div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
        <?php if (empty($items)): ?>
          <div style="text-align:center;padding:30px 10px;color:#475569;font-size:12px;">Trống</div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<script>
document.querySelectorAll('.pipe-body').forEach(col => {
  new Sortable(col, {
    group: 'pipeline', animation: 220, ghostClass: 'pipe-ghost',
    onStart: () => document.querySelectorAll('.pipe-body').forEach(c => c.classList.add('drag-over')),
    onEnd: function(evt) {
      document.querySelectorAll('.pipe-body').forEach(c => c.classList.remove('drag-over'));
      fetch('?c=pipeline&a=move', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'id=' + evt.item.dataset.id + '&status=' + evt.to.dataset.status
      }).then(r => r.json()).then(res => {
        if (res.ok) Swal.fire({
          toast: true, position: 'top-end', icon: 'success',
          title: 'Đã cập nhật', showConfirmButton: false, timer: 1500,
          background: 'rgba(15,23,42,.95)', color: '#e2e8f0'
        });
      });
    }
  });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>