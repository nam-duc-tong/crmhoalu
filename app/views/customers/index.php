<?php
$pageTitle = 'Khách hàng';
$activePage = 'customer';
include __DIR__ . '/../layouts/header.php';
?>

<div class="card mb-4">
  <div class="card-body">
    <form method="get" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
      <input type="hidden" name="c" value="customer">
      <div style="flex:1;min-width:240px;position:relative;">
        <svg viewBox="0 0 24 24" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:#64748b;pointer-events:none;stroke:currentColor;fill:none;stroke-width:2;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" name="q" value="<?= Helper::e($q) ?>" placeholder="Tìm tên, SĐT, mã KH..." style="width:100%;height:44px;padding:0 14px 0 42px;background:rgba(10,14,26,.6);border:1.5px solid rgba(148,163,184,.12);border-radius:11px;color:#f1f5f9;font-size:13.5px;">
      </div>
      <select name="status" onchange="this.form.submit()" style="height:44px;padding:0 40px 0 14px;background:rgba(10,14,26,.6);border:1.5px solid rgba(148,163,184,.12);border-radius:11px;color:#f1f5f9;font-size:13.5px;appearance:none;-webkit-appearance:none;background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;);background-repeat:no-repeat;background-position:right 14px center;">
        <option value="">Tất cả trạng thái</option>
        <?php foreach (['new','contacted','appointment','consulted','surgery','done','lost'] as $s): ?>
          <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= Helper::statusLabel($s) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Lọc
      </button>
    </form>
  </div>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Mã</th><th>Khách hàng</th><th>Liên hệ</th><th>Bệnh lý</th>
          <th>Nguồn</th><th>NV phụ trách</th><th>Trạng thái</th><th style="width:70px"></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($customers)): ?>
          <tr><td colspan="8">
            <div class="empty">
              <svg viewBox="0 0 24 24"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
              <p>Chưa có khách hàng nào</p>
            </div>
          </td></tr>
        <?php else: foreach ($customers as $c): ?>
          <tr>
            <td><span class="chip"><?= Helper::e($c['code']) ?></span></td>
            <td>
              <div style="display:flex;align-items:center;gap:11px;">
                <div class="ava ava-sm"><?= mb_substr($c['full_name'], 0, 1) ?></div>
                <div>
                  <a href="?c=customer&a=show&id=<?= $c['id'] ?>" style="font-weight:600;color:#fff;"><?= Helper::e($c['full_name']) ?></a>
                  <div style="font-size:11.5px;color:#64748b;margin-top:2px;"><?= Helper::e($c['city'] ?: '—') ?></div>
                </div>
              </div>
            </td>
            <td>
              <div style="font-size:13px;"><?= Helper::e($c['phone']) ?></div>
              <?php if ($c['email']): ?>
                <div style="font-size:11.5px;color:#64748b;margin-top:2px;"><?= Helper::e($c['email']) ?></div>
              <?php endif; ?>
            </td>
            <td><?= $c['interest'] ? '<span class="tag">' . Helper::e($c['interest']) . '</span>' : '<span style="color:#475569">—</span>' ?></td>
            <td style="color:#94a3b8;font-size:13px;"><?= Helper::e($c['source_name'] ?: '—') ?></td>
            <td style="font-size:13px;"><?= Helper::e($c['assigned_name'] ?: '—') ?></td>
            <td><span class="badge badge-<?= $c['status'] ?>"><span class="bdot"></span><?= Helper::statusLabel($c['status']) ?></span></td>
            <td>
              <a href="?c=customer&a=show&id=<?= $c['id'] ?>" class="btn btn-ghost btn-icon" title="Xem chi tiết">
                <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($pages > 1): ?>
  <div class="card-foot">
    <div style="font-size:13px;color:#64748b;">Tổng <strong style="color:#fff;"><?= $total ?></strong> khách hàng</div>
    <div style="display:flex;gap:4px;">
      <?php for ($p = 1; $p <= $pages; $p++): 
        $qs = http_build_query(array_merge($_GET, ['page' => $p])); ?>
        <a href="?<?= $qs ?>" style="min-width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:500;<?= $p == $page ? 'background:linear-gradient(135deg,#06b6d4,#3b82f6);color:#fff;' : 'background:rgba(10,14,26,.6);color:#94a3b8;' ?>"><?= $p ?></a>
      <?php endfor; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>