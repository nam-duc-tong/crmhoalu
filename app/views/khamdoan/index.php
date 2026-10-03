<?php
$pageTitle = 'Nguồn khách — SQL Server';
$activePage = 'khamdoan';
include __DIR__ . '/../layouts/header.php';

$syncRate = $totalAll > 0 ? round($syncedCount / $totalAll * 100) : 0;
?>

<!-- BANNER KẾT NỐI -->
<div class="card mb-4" style="background:linear-gradient(135deg, rgba(6,182,212,.1), rgba(139,92,246,.08));border-color:rgba(6,182,212,.3);">
  <div class="card-body" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
    <div style="width:52px;height:52px;border-radius:15px;background:linear-gradient(135deg,#06b6d4,#8b5cf6);display:flex;align-items:center;justify-content:center;box-shadow:0 8px 22px rgba(6,182,212,.35);">
      <svg viewBox="0 0 24 24" style="width:26px;height:26px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;">
        <path d="M3 11l19-9-9 19-2-8-8-2z"/>
      </svg>
    </div>
    <div style="flex:1;min-width:220px;">
      <div style="font-family:'Space Grotesk';font-size:16px;font-weight:700;color:#fff;">Đồng bộ Nguồn khách từ SQL Server</div>
      <div style="font-size:12.5px;color:#94a3b8;margin-top:2px;">
        <span style="color:#34d399;">● Đang kết nối</span>
        · Bảng: <code style="color:#22d3ee;">SHPT_PhongKham.DKKhamDoan</code>
      </div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <button class="btn btn-ghost" onclick="syncPage()" style="border-color:rgba(6,182,212,.4);color:#22d3ee;">
        <svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"/></svg>
        Đồng bộ trang này (<?= count($rows) ?>)
      </button>
      <button class="btn btn-primary" onclick="syncAll()">
        <svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
        Tất cả (<?= $totalAll ?>)
      </button>
    </div>
  </div>
</div>

<!-- STATS -->
<div class="stats mb-5">
  <div class="stat c-cyan gsap-fade">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg></div>
    <div class="stat-val" data-count="<?= $totalAll ?>">0</div>
    <div class="stat-lbl">Tổng nguồn trên SQL Server</div>
    <div class="stat-hint">Từ DKKhamDoan</div>
  </div>

  <div class="stat c-emerald gsap-fade" style="animation-delay:.06s">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
    <div class="stat-val" data-count="<?= $syncedCount ?>">0</div>
    <div class="stat-lbl">Đã đồng bộ vào CRM</div>
    <div class="stat-hint"><strong><?= $syncRate ?>%</strong> hoàn thành</div>
  </div>

  <div class="stat c-amber gsap-fade" style="animation-delay:.12s">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    <div class="stat-val" data-count="<?= $activeCount ?>">0</div>
    <div class="stat-lbl">Nguồn đang hoạt động</div>
    <div class="stat-hint">Còn hiệu lực</div>
  </div>

  <div class="stat c-purple gsap-fade" style="animation-delay:.18s">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></div>
    <div class="stat-val" data-count="<?= count($categories) ?>">0</div>
    <div class="stat-lbl">Phân loại nguồn</div>
    <div class="stat-hint">CSR, KX, MKT...</div>
  </div>
</div>

<!-- PHÂN LOẠI -->
<?php if (!empty($categories)): ?>
<div class="card mb-4 gsap-fade">
  <div class="card-head">
    <h3>
      <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
      Phân loại nguồn
    </h3>
  </div>
  <div class="card-body">
    <div style="display:flex;flex-wrap:wrap;gap:10px;">
      <?php
      $colors = ['#06b6d4','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#6366f1'];
      foreach ($categories as $i => $c):
        $color = $colors[$i % count($colors)];
      ?>
        <div class="quick-pill" style="border-color:<?= $color ?>40;background:<?= $color ?>10;">
          <span style="width:8px;height:8px;border-radius:50%;background:<?= $color ?>;box-shadow:0 0 8px <?= $color ?>;"></span>
          <strong style="color:#fff;"><?= Helper::e($c['Phanloai'] ?: 'Khác') ?></strong>
          <span style="color:<?= $color ?>;font-weight:700;"><?= $c['total'] ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- BỘ LỌC -->
<div class="card mb-4">
  <div class="card-body">
    <form method="get" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
      <input type="hidden" name="c" value="khamdoan">
      <div style="flex:1;min-width:240px;position:relative;">
        <svg viewBox="0 0 24 24" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:#64748b;pointer-events:none;stroke:currentColor;fill:none;stroke-width:2;">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input type="text" name="q" value="<?= Helper::e($q) ?>" placeholder="Tìm tên, mã, phân loại..."
               style="width:100%;height:44px;padding:0 14px 0 42px;background:rgba(15,23,42,.7);border:1.5px solid rgba(148,163,184,.18);border-radius:11px;color:#f1f5f9;font-size:13.5px;">
      </div>
      <button type="submit" class="btn btn-primary" style="height:44px;">
        <svg viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Tìm
      </button>
      <?php if ($q): ?>
        <a href="?c=khamdoan" class="btn btn-ghost" style="height:44px;">Xóa lọc</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- BẢNG NGUỒN -->
<div class="card gsap-fade">
  <div class="card-head">
    <h3>
      <svg viewBox="0 0 24 24"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>
      Danh sách nguồn khách
      <span class="badge badge-new" style="margin-left:8px;">
        <span class="bdot"></span>Trang <?= $page ?>/<?= $pages ?> · <?= $total ?> nguồn
      </span>
    </h3>
    <div class="quick-pill"><span class="dot-live"></span>Real-time từ SQL Server</div>
  </div>

  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th style="width:60px;">ID</th>
          <th>Tên nguồn khách</th>
          <th style="width:150px;">Ngày áp dụng</th>
          <th style="width:120px;text-align:center;">Trạng thái</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="7">
            <div class="empty empty-anim">
              <svg viewBox="0 0 24 24"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>
              <p>Không có nguồn nào</p>
            </div>
          </td></tr>
        <?php else: foreach ($rows as $i => $r):
          $isSynced = isset($syncedIds[$r['ID']]);
          $isActive = empty($r['Denngay']) || strtotime($r['Denngay']) >= time();
        ?>
          <tr class="gsap-fade" style="animation-delay:<?= $i * .02 ?>s">
            <td><span class="chip"><?= $r['ID'] ?></span></td>
           
            <td>
              <div style="font-weight:600;color:#fff;font-size:13.5px;"><?= Helper::e($r['Ten'] ?: '—') ?></div>
              <?php if ($r['Phanloai']): ?>
                <div style="font-size:11.5px;color:#64748b;margin-top:2px;"><?= Helper::e($r['Phanloai']) ?></div>
              <?php endif; ?>
            </td>
            <td style="font-size:12px;color:#94a3b8;">
              <?php if ($r['Tungay']): ?>
                <div><?= date('d/m/Y', strtotime($r['Tungay'])) ?></div>
              <?php endif; ?>
              <?php if ($r['Denngay']): ?>
                <div style="color:#64748b;">→ <?= date('d/m/Y', strtotime($r['Denngay'])) ?></div>
              <?php else: ?>
                <div style="color:#64748b;">→ <i>Không giới hạn</i></div>
              <?php endif; ?>
            </td>
            <td style="text-align:center;">
                <span class="badge badge-surgery"><span class="bdot"></span>Hoạt động</span>
            </td>
            
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <!-- PHÂN TRANG -->
  <?php if ($pages > 1): ?>
  <div class="card-foot" style="justify-content:space-between;flex-wrap:wrap;gap:12px;">

    <div style="font-size:13px;color:#94a3b8;">
      Hiển thị <strong style="color:#fff;"><?= ($page - 1) * $perPage + 1 ?>-<?= min($page * $perPage, $total) ?></strong>
      trong <strong style="color:#22d3ee;"><?= $total ?></strong> nguồn
      <?php if ($q): ?>
        <span style="color:#fbbf24;"> · Lọc: "<?= Helper::e($q) ?>"</span>
      <?php endif; ?>
    </div>

    <div style="display:flex;gap:4px;align-items:center;flex-wrap:wrap;">

      <?php if ($page > 1):
        $qs = http_build_query(array_merge($_GET, ['page' => 1]));
      ?>
        <a href="?<?= $qs ?>" class="page-btn" data-tip="Trang đầu">
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/>
          </svg>
        </a>
      <?php endif; ?>

      <?php if ($page > 1):
        $qs = http_build_query(array_merge($_GET, ['page' => $page - 1]));
      ?>
        <a href="?<?= $qs ?>" class="page-btn">
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
          </svg>
        </a>
      <?php endif; ?>

      <?php
      $range = 2;
      $start = max(1, $page - $range);
      $end   = min($pages, $page + $range);

      if ($start > 1) {
          $qs = http_build_query(array_merge($_GET, ['page' => 1]));
          echo '<a href="?' . $qs . '" class="page-btn">1</a>';
          if ($start > 2) echo '<span style="color:#475569;padding:0 6px;">...</span>';
      }

      for ($p = $start; $p <= $end; $p++):
        $qs = http_build_query(array_merge($_GET, ['page' => $p]));
      ?>
        <a href="?<?= $qs ?>" class="page-btn <?= $p == $page ? 'page-active' : '' ?>"><?= $p ?></a>
      <?php endfor;

      if ($end < $pages) {
          if ($end < $pages - 1) echo '<span style="color:#475569;padding:0 6px;">...</span>';
          $qs = http_build_query(array_merge($_GET, ['page' => $pages]));
          echo '<a href="?' . $qs . '" class="page-btn">' . $pages . '</a>';
      }
      ?>

      <?php if ($page < $pages):
        $qs = http_build_query(array_merge($_GET, ['page' => $page + 1]));
      ?>
        <a href="?<?= $qs ?>" class="page-btn">
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </a>
      <?php endif; ?>

      <?php if ($page < $pages):
        $qs = http_build_query(array_merge($_GET, ['page' => $pages]));
      ?>
        <a href="?<?= $qs ?>" class="page-btn" data-tip="Trang cuối">
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/>
          </svg>
        </a>
      <?php endif; ?>

      <div style="display:flex;align-items:center;gap:6px;margin-left:8px;padding-left:12px;border-left:1px solid rgba(148,163,184,.15);">
        <span style="font-size:12px;color:#64748b;">Đi tới:</span>
        <input type="number" min="1" max="<?= $pages ?>" value="<?= $page ?>"
               onkeydown="if(event.key==='Enter') jumpTo(this.value)"
               style="width:60px;height:34px;padding:0 8px;background:rgba(15,23,42,.7);border:1.5px solid rgba(148,163,184,.18);border-radius:9px;color:#fff;font-size:13px;text-align:center;">
      </div>
    </div>
  </div>
  <?php endif; ?>

</div>

<style>
.page-btn {
  min-width: 36px;
  height: 36px;
  padding: 0 10px;
  border-radius: 9px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: rgba(30,41,59,.6);
  color: #94a3b8;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: all .2s cubic-bezier(.16,1,.3,1);
  border: 1px solid rgba(148,163,184,.1);
  position: relative;
}
.page-btn:hover {
  background: rgba(148,163,184,.12);
  color: #fff;
  transform: translateY(-2px);
  border-color: rgba(34,211,238,.4);
}
.page-btn.page-active {
  background: linear-gradient(135deg, #06b6d4, #3b82f6);
  color: #fff;
  border-color: transparent;
  box-shadow: 0 6px 16px rgba(6,182,212,.4);
  transform: scale(1.05);
}
.page-btn.page-active:hover {
  transform: scale(1.08);
  box-shadow: 0 8px 22px rgba(6,182,212,.55);
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<script>
function jumpTo(p) {
  const url = new URL(window.location.href);
  url.searchParams.set('page', p);
  window.location.href = url.toString();
}

async function syncOne(id, btn) {
  btn.disabled = true;
  const originalHTML = btn.innerHTML;
  btn.innerHTML = '<svg viewBox="0 0 24 24" style="animation:spin 1s linear infinite;width:14px;height:14px;"><circle cx="12" cy="12" r="10" opacity="0.25" fill="none" stroke="currentColor" stroke-width="3"/><path d="M12 2a10 10 0 0 1 10 10" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>';

  try {
    const res = await fetch('?c=khamdoan&a=syncOne', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'id=' + id
    });
    const data = await res.json();

    if (data.ok) {
      Swal.fire({
        toast: true, position: 'top-end', icon: data.existing ? 'info' : 'success',
        title: data.existing ? 'Nguồn này đã có trong CRM' : 'Đã đồng bộ vào CRM!',
        showConfirmButton: false, timer: 2000,
        background: 'rgba(26,35,50,.95)', color: '#e2e8f0'
      });
      if (!data.existing && window.confetti) window.confetti(window.innerWidth - 100, 100);
      setTimeout(() => location.reload(), 800);
    } else {
      Swal.fire({
        icon: 'error', title: 'Lỗi',
        text: data.error || 'Không đồng bộ được',
        background: '#1a2332', color: '#e2e8f0'
      });
      btn.disabled = false;
      btn.innerHTML = originalHTML;
    }
  } catch (e) {
    Swal.fire({icon:'error', title:'Lỗi kết nối', text:e.message, background:'#1a2332', color:'#e2e8f0'});
    btn.disabled = false;
    btn.innerHTML = originalHTML;
  }
}

async function syncPage() {
  const r = await Swal.fire({
    title: 'Đồng bộ trang này?',
    html: `Sẽ nhập <b>${<?= count($rows) ?>}</b> nguồn từ trang hiện tại vào CRM.`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Đồng bộ',
    cancelButtonText: 'Hủy',
    confirmButtonColor: '#06b6d4',
    background: '#1a2332', color: '#e2e8f0'
  });
  if (!r.isConfirmed) return;

  const ids = <?= json_encode(array_column($rows, 'ID')) ?>;

  Swal.fire({
    title: 'Đang đồng bộ...',
    html: '<div id="progress">0/' + ids.length + '</div>',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
    background: '#1a2332', color: '#e2e8f0'
  });

  let added = 0, skipped = 0;
  for (const id of ids) {
    try {
      const res = await fetch('?c=khamdoan&a=syncOne', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + id
      });
      const data = await res.json();
      if (data.ok) {
        if (data.existing) skipped++;
        else added++;
      }
      document.getElementById('progress').textContent = (added + skipped) + '/' + ids.length;
    } catch (e) {}
  }

  Swal.fire({
    icon: 'success', title: 'Đồng bộ hoàn tất!',
    html: '<b style="color:#34d399;">Đã thêm: ' + added + '</b><br><b style="color:#fbbf24;">Bỏ qua: ' + skipped + '</b>',
    background: '#1a2332', color: '#e2e8f0'
  }).then(() => location.reload());

  if (window.confetti) window.confetti();
}

async function syncAll() {
  const r = await Swal.fire({
    title: 'Đồng bộ tất cả nguồn?',
    html: 'Sẽ nhập <b><?= $totalAll ?></b> nguồn từ SQL Server vào CRM.<br><small style="color:#94a3b8;">Nguồn đã có sẽ tự động bỏ qua.</small>',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Đồng bộ ngay',
    cancelButtonText: 'Hủy',
    confirmButtonColor: '#06b6d4',
    background: '#1a2332', color: '#e2e8f0'
  });
  if (!r.isConfirmed) return;

  Swal.fire({
    title: 'Đang đồng bộ...',
    html: 'Vui lòng đợi trong giây lát',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
    background: '#1a2332', color: '#e2e8f0'
  });

  try {
    const res = await fetch('?c=khamdoan&a=syncAll', { method: 'POST' });
    const data = await res.json();

    if (data.ok) {
      Swal.fire({
        icon: 'success', title: 'Đồng bộ hoàn tất!',
        html: '<div style="text-align:left;line-height:2;">' +
              '<b style="color:#34d399;">Đã thêm:</b> ' + data.added + ' nguồn<br>' +
              '<b style="color:#fbbf24;">Bỏ qua:</b> ' + data.skipped + ' nguồn<br>' +
              '<b style="color:#22d3ee;">Tổng xử lý:</b> ' + data.total + ' nguồn</div>',
        background: '#1a2332', color: '#e2e8f0'
      }).then(() => location.reload());
      if (window.confetti) window.confetti();
    } else {
      Swal.fire({ icon: 'error', title: 'Lỗi', text: data.error, background: '#1a2332', color: '#e2e8f0' });
    }
  } catch (e) {
    Swal.fire({ icon:'error', title:'Lỗi', text:e.message, background:'#1a2332', color:'#e2e8f0'});
  }
}

async function unsyncOne(id, name) {
  const r = await Swal.fire({
    title: 'Bỏ đồng bộ nguồn?',
    html: 'Xóa <b>' + name + '</b> khỏi CRM?<br><small style="color:#94a3b8;">Nguồn này sẽ bị xóa trong CRM nhưng vẫn còn trong SQL Server.</small>',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Xóa khỏi CRM',
    cancelButtonText: 'Hủy',
    confirmButtonColor: '#ef4444',
    background: '#1a2332', color: '#e2e8f0'
  });
  if (!r.isConfirmed) return;

  const res = await fetch('?c=khamdoan&a=unsync', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'id=' + id
  });
  const data = await res.json();

  if (data.ok) {
    Swal.fire({
      toast: true, position: 'top-end', icon: 'success',
      title: 'Đã bỏ đồng bộ',
      showConfirmButton: false, timer: 1500,
      background: 'rgba(26,35,50,.95)', color: '#e2e8f0'
    });
    setTimeout(() => location.reload(), 800);
  } else {
    Swal.fire({icon:'error', title:'Lỗi', text:data.error, background:'#1a2332', color:'#e2e8f0'});
  }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>