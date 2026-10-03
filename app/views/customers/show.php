<?php
$pageTitle = 'Chi tiết khách hàng';
$activePage = 'customer';
include __DIR__ . '/../layouts/header.php';

$statusGrad = [
  'new'         => 'linear-gradient(135deg,#06b6d4,#0891b2)',
  'contacted'   => 'linear-gradient(135deg,#3b82f6,#2563eb)',
  'appointment' => 'linear-gradient(135deg,#f59e0b,#d97706)',
  'consulted'   => 'linear-gradient(135deg,#8b5cf6,#7c3aed)',
  'surgery'     => 'linear-gradient(135deg,#10b981,#059669)',
  'done'        => 'linear-gradient(135deg,#10b981,#059669)',
  'lost'        => 'linear-gradient(135deg,#ef4444,#dc2626)',
][$customer['status']] ?? 'linear-gradient(135deg,#64748b,#475569)';
?>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:16px;" class="grid-2">

  <div class="card">
    <div style="background:<?= $statusGrad ?>;height:90px;position:relative;overflow:hidden;">
      <div style="position:absolute;width:200px;height:200px;background:rgba(255,255,255,.15);border-radius:50%;top:-100px;right:-60px;"></div>
      <div style="position:absolute;width:120px;height:120px;background:rgba(255,255,255,.1);border-radius:50%;bottom:-60px;left:-30px;"></div>
    </div>
    <div style="text-align:center;margin-top:-50px;padding:0 22px 22px;position:relative;z-index:2;">
      <div class="ava ava-lg" style="margin:0 auto;background:<?= $statusGrad ?>;border:4px solid #0d1526;font-family:'Space Grotesk';display:flex;">
        <?= mb_substr($customer['full_name'], 0, 1) ?>
      </div>
      <h2 style="font-family:'Space Grotesk';font-size:20px;font-weight:700;color:#fff;margin-top:14px;"><?= Helper::e($customer['full_name']) ?></h2>
      <div style="margin-top:8px;display:flex;justify-content:center;gap:8px;flex-wrap:wrap;">
        <span class="chip"><?= Helper::e($customer['code']) ?></span>
        <span class="badge badge-<?= $customer['status'] ?>"><span class="bdot"></span><?= Helper::statusLabel($customer['status']) ?></span>
      </div>
    </div>
    <div style="padding:0 22px 22px;">
      <?php 
      $info = [
        ['M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z', 'SĐT', $customer['phone']],
        ['M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z M22 6l-10 7L2 6', 'Email', $customer['email'] ?: '—'],
        ['M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z M12 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6z', 'Địa chỉ', $customer['address'] ?: '—'],
        ['M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6z', 'Bệnh lý', $customer['interest'] ?: 'Chưa rõ'],
        ['M3 11l19-9-9 19-2-8-8-2z', 'Nguồn', $customer['source_name'] ?: '—'],
        ['M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8z', 'NV phụ trách', $customer['assigned_name'] ?: '—'],
      ];
      foreach ($info as $row): ?>
        <div style="display:flex;gap:12px;align-items:flex-start;padding:11px 0;border-bottom:1px solid rgba(148,163,184,.05);">
          <div style="width:34px;height:34px;border-radius:10px;background:rgba(6,182,212,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#22d3ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="<?= $row[0] ?>"/></svg>
          </div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:10px;font-weight:700;color:#64748b;letter-spacing:.08em;text-transform:uppercase;"><?= $row[1] ?></div>
            <div style="font-size:13.5px;color:#e2e8f0;margin-top:3px;word-break:break-word;"><?= Helper::e($row[2]) ?></div>
          </div>
        </div>
      <?php endforeach; ?>

      <div style="padding-top:16px;margin-top:12px;border-top:1px solid rgba(148,163,184,.06);">
        <div style="font-size:10px;font-weight:700;color:#64748b;letter-spacing:.08em;text-transform:uppercase;">Giá trị dự kiến</div>
        <div style="font-family:'Space Grotesk';font-size:22px;font-weight:700;background:linear-gradient(135deg,#22d3ee,#a78bfa);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;margin-top:4px;">
          <?= Helper::moneyFull($customer['estimated_value']) ?>
        </div>
      </div>
    </div>
    <div class="card-foot" style="justify-content:center;">
      <button type="button" class="btn btn-primary" onclick="document.getElementById('editModal').classList.add('show')" style="width:100%;justify-content:center;">
        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Cập nhật thông tin
      </button>
    </div>
  </div>

  <div>
    <form method="post" action="?c=customer&a=addActivity" class="card mb-4">
      <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
      <div class="card-head">
        <h3>
          <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          Ghi nhận tương tác mới
        </h3>
      </div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr 2fr auto;gap:10px;align-items:end;">
          <div class="fg">
            <label>Loại</label>
            <select name="type">
              <option value="call">📞 Gọi điện</option>
              <option value="sms">💬 SMS</option>
              <option value="email">✉️ Email</option>
              <option value="meeting">🤝 Gặp mặt</option>
              <option value="note">📝 Ghi chú</option>
            </select>
          </div>
          <div class="fg">
            <label>Kết quả</label>
            <select name="result">
              <option value="pending">⏳ Chờ</option>
              <option value="success">✅ Thành công</option>
              <option value="no_answer">📵 Không nghe</option>
              <option value="fail">❌ Thất bại</option>
            </select>
          </div>
          <div class="fg">
            <label>Nội dung</label>
            <input type="text" name="content" placeholder="Nội dung tương tác..." required>
          </div>
          <button type="submit" class="btn btn-primary" style="height:44px;">
            <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
        </div>
      </div>
    </form>

    <div class="card">
      <div class="card-head">
        <h3>
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Lịch sử tương tác
        </h3>
        <span class="badge badge-new"><span class="bdot"></span><?= count($activities) ?></span>
      </div>
      <div class="card-body">
        <?php if (empty($activities)): ?>
          <div class="empty">
            <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <p>Chưa có tương tác nào</p>
          </div>
        <?php else: ?>
          <div class="timeline">
            <?php 
            $typeIcons = [
              'call' => 'M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z',
              'sms' => 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z',
              'email' => 'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z M22 6l-10 7L2 6',
              'meeting' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M9 7a4 4 0 1 0 0 8 4 4 0 0 0 0-8z M23 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75',
              'note' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6',
              'status_change' => 'M17 1l4 4-4 4 M3 11V9a4 4 0 0 1 4-4h14 M7 23l-4-4 4-4 M21 13v2a4 4 0 0 1-4 4H3',
              'surgery' => 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z',
            ];
            foreach ($activities as $i => $a): 
              $ip = $typeIcons[$a['type']] ?? 'M12 2L2 7l10 5 10-5-10-5z';
            ?>
            <div class="tl-item" style="animation-delay:<?= $i * 0.04 ?>s">
              <div class="tl-icon tl-<?= $a['type'] ?>">
                <svg viewBox="0 0 24 24"><path d="<?= $ip ?>"/></svg>
              </div>
              <div class="tl-body">
                <div style="display:flex;justify-content:space-between;gap:8px;">
                  <strong><?= Helper::e($a['user_name']) ?></strong>
                  <small><?= Helper::timeAgo($a['created_at']) ?></small>
                </div>
                <p><?= Helper::e($a['content']) ?></p>
                <?php if ($a['result'] && $a['result'] !== 'pending'): 
                  $rMap = ['success'=>['#34d399','rgba(16,185,129,.12)','Thành công'],'fail'=>['#f87171','rgba(239,68,68,.12)','Thất bại'],'no_answer'=>['#fbbf24','rgba(245,158,11,.12)','Không nghe']];
                  $r = $rMap[$a['result']] ?? ['#94a3b8','rgba(148,163,184,.12)',''];
                ?>
                  <span style="display:inline-block;margin-top:6px;padding:2px 9px;border-radius:6px;font-size:10px;font-weight:700;color:<?= $r[0] ?>;background:<?= $r[1] ?>;"><?= $r[2] ?></span>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-ov" id="editModal">
  <form method="post" action="?c=customer&a=update" class="modal">
    <input type="hidden" name="id" value="<?= $customer['id'] ?>">
    <div class="modal-head">
      <h3>Cập nhật khách hàng</h3>
      <button type="button" class="modal-close" onclick="document.getElementById('editModal').classList.remove('show')">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="fg col-6"><label>Họ tên <span class="req">*</span></label><input type="text" name="full_name" value="<?= Helper::e($customer['full_name']) ?>" required></div>
        <div class="fg col-6"><label>SĐT <span class="req">*</span></label><input type="tel" name="phone" value="<?= Helper::e($customer['phone']) ?>" required></div>
        <div class="fg col-6"><label>Email</label><input type="email" name="email" value="<?= Helper::e($customer['email']) ?>"></div>
        <div class="fg col-3"><label>Giới tính</label>
          <select name="gender">
            <option value="male" <?= $customer['gender'] === 'male' ? 'selected' : '' ?>>Nam</option>
            <option value="female" <?= $customer['gender'] === 'female' ? 'selected' : '' ?>>Nữ</option>
            <option value="other" <?= $customer['gender'] === 'other' ? 'selected' : '' ?>>Khác</option>
          </select>
        </div>
        <div class="fg col-3"><label>Năm sinh</label><input type="number" name="birth_year" value="<?= $customer['birth_year'] ?>"></div>
        <div class="fg col-8"><label>Địa chỉ</label><input type="text" name="address" value="<?= Helper::e($customer['address']) ?>"></div>
        <div class="fg col-4"><label>Tỉnh/Thành</label><input type="text" name="city" value="<?= Helper::e($customer['city']) ?>"></div>
        <div class="fg col-6"><label>Nguồn</label>
          <select name="source_id">
            <option value="">--</option>
            <?php foreach ($sources as $s): ?>
              <option value="<?= $s['id'] ?>" <?= $customer['source_id'] == $s['id'] ? 'selected' : '' ?>><?= Helper::e($s['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="fg col-6"><label>NV phụ trách</label>
          <select name="assigned_to">
            <option value="">--</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= $u['id'] ?>" <?= $customer['assigned_to'] == $u['id'] ? 'selected' : '' ?>><?= Helper::e($u['full_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="fg col-6"><label>Trạng thái</label>
          <select name="status">
            <?php foreach (['new','contacted','appointment','consulted','surgery','done','lost'] as $s): ?>
              <option value="<?= $s ?>" <?= $customer['status'] === $s ? 'selected' : '' ?>><?= Helper::statusLabel($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="fg col-6"><label>Bệnh lý</label><input type="text" name="interest" value="<?= Helper::e($customer['interest']) ?>"></div>
        <div class="fg col-6"><label>Giá trị dự kiến</label><input type="number" name="estimated_value" value="<?= $customer['estimated_value'] ?>"></div>
        <div class="fg col-6"><label>Follow-up</label><input type="datetime-local" name="next_followup_at" value="<?= $customer['next_followup_at'] ? date('Y-m-d\TH:i', strtotime($customer['next_followup_at'])) : '' ?>"></div>
        <div class="fg col-12"><label>Ghi chú</label><textarea name="note"><?= Helper::e($customer['note']) ?></textarea></div>
      </div>
    </div>
    <div class="modal-foot">
      <button type="button" class="btn btn-ghost" onclick="document.getElementById('editModal').classList.remove('show')">Hủy</button>
      <button type="submit" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        Lưu thay đổi
      </button>
    </div>
  </form>
</div>

<script>
document.getElementById('editModal').addEventListener('click', function(e){ if(e.target===this) this.classList.remove('show'); });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>