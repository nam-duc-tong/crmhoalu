<?php
$pageTitle = 'Báo cáo';
$activePage = 'report';
include __DIR__ . '/../layouts/header.php';
?>

<form method="get" class="card mb-4">
  <input type="hidden" name="c" value="report">
  <div class="card-body" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
    <div class="fg" style="min-width:180px;">
      <label>Từ ngày</label>
      <input type="date" name="from" value="<?= $from ?>">
    </div>
    <div class="fg" style="min-width:180px;">
      <label>Đến ngày</label>
      <input type="date" name="to" value="<?= $to ?>">
    </div>
    <button type="submit" class="btn btn-primary">
      <svg viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      Lọc
    </button>
  </div>
</form>

<div class="grid-2">
  <div class="card">
    <div class="card-head">
      <h3>
        <svg viewBox="0 0 24 24"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>
        Hiệu quả theo nguồn
      </h3>
    </div>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead><tr><th>Nguồn</th><th style="text-align:center;">Leads</th><th style="text-align:center;">Chốt</th><th style="text-align:right;">Doanh thu</th></tr></thead>
        <tbody>
          <?php foreach ($bySource as $r): 
            $rate = $r['total'] ? round($r['won'] / $r['total'] * 100) : 0;
          ?>
          <tr>
            <td><strong style="color:#fff;"><?= Helper::e($r['name']) ?></strong></td>
            <td style="text-align:center;"><?= $r['total'] ?></td>
            <td style="text-align:center;"><span class="badge badge-new"><span class="bdot"></span><?= $r['won'] ?> (<?= $rate ?>%)</span></td>
            <td style="text-align:right;font-family:'Space Grotesk';font-weight:700;color:#34d399;"><?= Helper::money($r['revenue']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <h3>
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        Hiệu suất nhân viên
      </h3>
    </div>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead><tr><th>Nhân viên</th><th style="text-align:center;">KH</th><th style="text-align:center;">Chốt</th><th style="text-align:right;">KPI</th></tr></thead>
        <tbody>
          <?php foreach ($bySale as $r): 
            $kpi = $r['kpi_target'] ? round($r['revenue'] / $r['kpi_target'] * 100) : 0;
            $kpiColor = $kpi >= 100 ? '#34d399' : ($kpi >= 70 ? '#fbbf24' : '#f87171');
          ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <div class="ava ava-sm"><?= mb_substr($r['full_name'], 0, 1) ?></div>
                <strong style="color:#fff;"><?= Helper::e($r['full_name']) ?></strong>
              </div>
            </td>
            <td style="text-align:center;"><?= $r['total'] ?></td>
            <td style="text-align:center;"><?= $r['won'] ?></td>
            <td style="text-align:right;min-width:120px;">
              <div class="progress"><div class="progress-fill" style="width:<?= min(100, $kpi) ?>%"></div></div>
              <div style="font-size:11px;font-weight:700;color:<?= $kpiColor ?>;margin-top:4px;"><?= $kpi ?>%</div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>