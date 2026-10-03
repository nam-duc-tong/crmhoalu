<style>
  .heatmap-cell {
    aspect-ratio: 1;
    border-radius: 3px;
    transition: all .2s;
    cursor: pointer;
  }

  .heatmap-cell:hover {
    transform: scale(1.5);
    box-shadow: 0 0 0 2px #06b6d4;
    z-index: 10;
    position: relative;
  }
</style>
<?php
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
include __DIR__ . '/../layouts/header.php';

$pipelineLabels = ['new' => 'Mới', 'contacted' => 'Đã LH', 'appointment' => 'Hẹn khám', 'consulted' => 'Đã TV', 'surgery' => 'Đã mổ'];
$pipelineVals = [];
foreach (array_keys($pipelineLabels) as $k) $pipelineVals[] = (int)($pipelineMap[$k] ?? 0);
$totalAll = array_sum($pipelineVals) ?: 1;
$wonCount = ($pipelineMap['surgery'] ?? 0) + ($pipelineMap['done'] ?? 0);
$rate = round($wonCount / $totalAll * 100);
?>

<div class="stats">
  <div class="stat c-cyan" style="animation-delay:0s">
    <div class="stat-icon">
      <svg viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
        <circle cx="9" cy="7" r="4" />
      </svg>
    </div>
    <div class="stat-val" data-count="<?= $stats['total'] ?>">0</div>
    <div class="stat-lbl">Tổng khách hàng</div>
    <div class="stat-hint"><strong>+<?= $stats['new_today'] ?></strong> hôm nay</div>
  </div>

  <div class="stat c-amber" style="animation-delay:.06s">
    <div class="stat-icon">
      <svg viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="18" rx="2" />
        <line x1="16" y1="2" x2="16" y2="6" />
        <line x1="8" y1="2" x2="8" y2="6" />
        <line x1="3" y1="10" x2="21" y2="10" />
      </svg>
    </div>
    <div class="stat-val" data-count="<?= $stats['appointment'] ?>">0</div>
    <div class="stat-lbl">Lịch hẹn khám</div>
    <div class="stat-hint">Đang chờ khám</div>
  </div>

  <div class="stat c-emerald" style="animation-delay:.12s">
    <div class="stat-icon">
      <svg viewBox="0 0 24 24">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
      </svg>
    </div>
    <div class="stat-val" data-count="<?= $stats['surgery'] ?>">0</div>
    <div class="stat-lbl">Ca phẫu thuật</div>
    <div class="stat-hint">Đã mổ thành công</div>
  </div>

  <div class="stat c-purple" style="animation-delay:.18s">
    <div class="stat-icon">
      <svg viewBox="0 0 24 24">
        <line x1="12" y1="1" x2="12" y2="23" />
        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
      </svg>
    </div>
    <div class="stat-val" data-count="<?= $stats['revenue'] ?>">0</div>
    <div class="stat-lbl">Doanh thu dự kiến</div>
    <div class="stat-hint">Từ mổ & hoàn tất</div>
  </div>
</div>
<!-- ═══ MINI INSIGHTS ROW ═══ -->
<div class="grid-3 mb-5" style="grid-template-columns:repeat(auto-fit,minmax(280px,1fr));">

  <!-- Heatmap hoạt động -->
  <div class="card gsap-fade">
    <div class="card-head">
      <h3><svg viewBox="0 0 24 24">
          <rect x="3" y="3" width="7" height="7" />
          <rect x="14" y="3" width="7" height="7" />
          <rect x="14" y="14" width="7" height="7" />
          <rect x="3" y="14" width="7" height="7" />
        </svg>Hoạt động 30 ngày</h3>
    </div>
    <div class="card-body">
      <div style="display:grid;grid-template-columns:repeat(10,1fr);gap:4px;">
        <?php for ($i = 0; $i < 30; $i++):
          $intensity = rand(0, 4);
          $alpha = $intensity * 0.2 + 0.05;
        ?>
          <div class="heatmap-cell" style="background:rgba(6,182,212,<?= $alpha ?>);" data-tip="<?= $intensity * 5 ?> hoạt động"></div>
        <?php endfor; ?>
      </div>
      <div style="display:flex;justify-content:space-between;margin-top:12px;font-size:11px;color:#64748b;">
        <span>30 ngày trước</span>
        <span>Hôm nay</span>
      </div>
    </div>
  </div>

  <!-- Top nguồn khách -->
  <div class="card gsap-fade">
    <div class="card-head">
      <h3><svg viewBox="0 0 24 24">
          <path d="M3 11l19-9-9 19-2-8-8-2z" />
        </svg>Nguồn hiệu quả</h3>
    </div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
      <?php
      $topSources = [
        ['Facebook Ads', 45, '#06b6d4'],
        ['Google Ads', 32, '#8b5cf6'],
        ['TikTok', 18, '#ec4899'],
        ['Hotline', 12, '#f59e0b'],
      ];
      foreach ($topSources as $s): ?>
        <div>
          <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:6px;">
            <span style="color:#cbd5e1;"><?= $s[0] ?></span>
            <span style="color:<?= $s[2] ?>;font-weight:700;"><?= $s[1] ?>%</span>
          </div>
          <div class="progress">
            <div class="progress-fill" style="width:<?= $s[1] ?>%;background:linear-gradient(90deg,<?= $s[2] ?>,<?= $s[2] ?>aa);"></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Team hoạt động -->
  <div class="card gsap-fade">
    <div class="card-head">
      <h3><svg viewBox="0 0 24 24">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
        </svg>Team đang online</h3>
    </div>
    <div class="card-body">
      <div class="avatar-stack" style="justify-content:center;padding:10px 0;">
        <div class="ava">A</div>
        <div class="ava" style="background:linear-gradient(135deg,#f59e0b,#d97706)">B</div>
        <div class="ava" style="background:linear-gradient(135deg,#10b981,#059669)">C</div>
        <div class="ava" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">D</div>
        <div class="ava" style="background:linear-gradient(135deg,#ec4899,#db2777)">E</div>
        <div class="ava" style="background:rgba(148,163,184,.2);color:#94a3b8;font-size:12px;">+5</div>
      </div>
      <div style="text-align:center;margin-top:14px;">
        <div style="font-family:'Space Grotesk';font-size:26px;font-weight:700;color:#fff;" data-count="10">0</div>
        <div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Nhân viên online</div>
      </div>
    </div>
  </div>
</div>
<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:16px;" class="grid-2">
  <div class="card">
    <div class="card-head">
      <h3>
        <svg viewBox="0 0 24 24">
          <line x1="12" y1="20" x2="12" y2="10" />
          <line x1="18" y1="20" x2="18" y2="4" />
          <line x1="6" y1="20" x2="6" y2="16" />
        </svg>
        Phễu bán hàng
      </h3>
    </div>
    <div class="card-body">
      <div id="pipelineChart"></div>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <h3>
        <svg viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" />
          <polyline points="12 6 12 12 16 14" />
        </svg>
        Tỷ lệ chốt
      </h3>
    </div>
    <div class="card-body" style="text-align:center;">
      <div style="position:relative;width:170px;height:170px;margin:0 auto;">
        <svg viewBox="0 0 100 100" style="transform:rotate(-90deg);width:100%;height:100%;">
          <circle cx="50" cy="50" r="42" stroke="rgba(148,163,184,.1)" stroke-width="8" fill="none" />
          <circle cx="50" cy="50" r="42" stroke="url(#rg)" stroke-width="8" fill="none" stroke-linecap="round"
            stroke-dasharray="264" stroke-dashoffset="264" id="ring" data-offset="<?= 264 * (1 - $rate / 100) ?>" />
          <defs>
            <linearGradient id="rg" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#06b6d4" />
              <stop offset="100%" stop-color="#8b5cf6" />
            </linearGradient>
          </defs>
        </svg>
        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
          <div style="font-family:'Space Grotesk';font-size:36px;font-weight:700;background:linear-gradient(135deg,#22d3ee,#a78bfa);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;" data-count="<?= $rate ?>" data-suffix="%">0%</div>
          <div style="font-size:11px;color:#64748b;margin-top:4px;">Chốt thành công</div>
        </div>
      </div>
      <div class="grid-2" style="margin-top:20px;">
        <div style="background:rgba(10,14,26,.6);padding:12px;border-radius:12px;border:1px solid rgba(148,163,184,.08);">
          <div style="font-size:20px;font-weight:700;color:#34d399;font-family:'Space Grotesk';"><?= $wonCount ?></div>
          <div style="font-size:10px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;margin-top:4px;">Đã chốt</div>
        </div>
        <div style="background:rgba(10,14,26,.6);padding:12px;border-radius:12px;border:1px solid rgba(148,163,184,.08);">
          <div style="font-size:20px;font-weight:700;color:#f87171;font-family:'Space Grotesk';"><?= $totalAll - $wonCount ?></div>
          <div style="font-size:10px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;margin-top:4px;">Chưa chốt</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="grid-2">
  <?php if (!empty($topSales)): ?>
    <div class="card">
      <div class="card-head">
        <h3>
          <svg viewBox="0 0 24 24">
            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
            <path d="M4 22h16" />
            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z" />
          </svg>
          Bảng xếp hạng Sales
        </h3>
      </div>
      <?php foreach ($topSales as $i => $s):
        $r = $s['total'] ? round($s['won'] / $s['total'] * 100) : 0;
        $medal = $i < 3 ? 'linear-gradient(135deg,#fbbf24,#f59e0b)' : 'rgba(15,23,42,.6)';
        $medalColor = $i < 3 ? '#fff' : '#94a3b8';
      ?>
        <div style="display:flex;align-items:center;gap:12px;padding:14px 22px;border-bottom:1px solid rgba(148,163,184,.05);">
          <div style="width:32px;height:32px;border-radius:9px;background:<?= $medal ?>;display:flex;align-items:center;justify-content:center;font-weight:700;color:<?= $medalColor ?>;font-size:12px;flex-shrink:0;"><?= $i + 1 ?></div>
          <div class="ava ava-sm"><?= mb_substr($s['full_name'], 0, 1) ?></div>
          <div style="flex:1;min-width:0;">
            <div style="font-weight:600;color:#fff;font-size:13.5px;"><?= Helper::e($s['full_name']) ?></div>
            <div style="font-size:11px;color:#64748b;"><?= $s['total'] ?> KH • <?= $s['won'] ?> chốt</div>
          </div>
          <div style="text-align:right;flex-shrink:0;">
            <div style="font-family:'Space Grotesk';font-weight:700;color:#34d399;font-size:14px;"><?= Helper::money($s['revenue']) ?></div>
            <div class="progress" style="width:80px;margin-top:4px;">
              <div class="progress-fill" style="width:<?= $r ?>%"></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-head">
      <h3>
        <svg viewBox="0 0 24 24">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
          <path d="M13.73 21a2 2 0 0 1-3.46 0" />
        </svg>
        Nhắc chăm sóc
      </h3>
      <?php if (count($followups)): ?>
        <span class="badge badge-new"><span class="bdot"></span><?= count($followups) ?></span>
      <?php endif; ?>
    </div>
    <div style="max-height:400px;overflow-y:auto;">
      <?php if (empty($followups)): ?>
        <div class="empty">
          <svg viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
          </svg>
          <p>Đã xử lý hết! 🎉</p>
        </div>
        <?php else: foreach ($followups as $f): ?>
          <a href="?c=customer&a=show&id=<?= $f['id'] ?>" style="display:flex;align-items:center;gap:12px;padding:13px 22px;border-bottom:1px solid rgba(148,163,184,.05);transition:background .15s;" onmouseover="this.style.background='rgba(148,163,184,.04)'" onmouseout="this.style.background='transparent'">
            <div class="ava ava-sm"><?= mb_substr($f['full_name'], 0, 1) ?></div>
            <div style="flex:1;min-width:0;">
              <div style="font-weight:600;color:#fff;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= Helper::e($f['full_name']) ?></div>
              <div style="font-size:11px;color:#64748b;"><?= Helper::e($f['phone']) ?></div>
            </div>
            <span class="badge badge-<?= $f['status'] ?>"><span class="bdot"></span><?= Helper::statusLabel($f['status']) ?></span>
          </a>
      <?php endforeach;
      endif; ?>
    </div>
  </div>
</div>

<script>
  new ApexCharts(document.querySelector('#pipelineChart'), {
    chart: {
      type: 'bar',
      height: 300,
      toolbar: {
        show: false
      },
      background: 'transparent',
      animations: {
        enabled: true,
        easing: 'easeinout',
        speed: 800,
        animateGradually: {
          enabled: true,
          delay: 100
        }
      }
    },
    series: [{
      name: 'Khách hàng',
      data: <?= json_encode($pipelineVals) ?>
    }],
    plotOptions: {
      bar: {
        borderRadius: 10,
        columnWidth: '55%',
        distributed: true,
        dataLabels: {
          position: 'top'
        }
      }
    },
    colors: ['#06b6d4', '#3b82f6', '#f59e0b', '#8b5cf6', '#10b981'],
    dataLabels: {
      enabled: true,
      offsetY: -22,
      style: {
        fontSize: '13px',
        fontWeight: 700,
        colors: ['#e2e8f0']
      }
    },
    xaxis: {
      categories: <?= json_encode(array_values($pipelineLabels)) ?>,
      labels: {
        style: {
          colors: '#94a3b8',
          fontSize: '12px',
          fontWeight: 500
        }
      },
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false
      }
    },
    yaxis: {
      labels: {
        style: {
          colors: '#64748b',
          fontSize: '11px'
        }
      }
    },
    grid: {
      borderColor: 'rgba(148,163,184,.06)',
      strokeDashArray: 4,
      xaxis: {
        lines: {
          show: false
        }
      }
    },
    legend: {
      show: false
    },
    tooltip: {
      theme: 'dark'
    }
  }).render();

  setTimeout(() => {
    const ring = document.getElementById('ring');
    if (ring) {
      ring.style.transition = 'stroke-dashoffset 1.5s cubic-bezier(.16,1,.3,1)';
      ring.style.strokeDashoffset = ring.dataset.offset;
    }
  }, 400);
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>