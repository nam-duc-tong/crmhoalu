<?php
$pageTitle = 'Thêm khách hàng';
$activePage = 'customer';
include __DIR__ . '/../layouts/header.php';
?>

<form method="post" action="?c=customer&a=store" class="form-card">

  <div class="card">
    <div class="card-head">
      <h3>
        <svg viewBox="0 0 24 24">
          <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
          <circle cx="8.5" cy="7" r="4" />
          <line x1="20" y1="8" x2="20" y2="14" />
          <line x1="23" y1="11" x2="17" y2="11" />
        </svg>
        Thông tin khách hàng mới
      </h3>
    </div>

    <div class="card-body">

      <!-- SECTION 1: Cơ bản -->
      <div class="form-section">
        <div class="form-section-title">
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="4" />
            <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
          </svg>
          Thông tin cơ bản
        </div>
        <div class="form-grid">
          <div class="fg col-6">
            <label>Họ tên <span class="req">*</span></label>
            <input type="text" name="full_name" placeholder="Nguyễn Văn A" required autofocus>
          </div>
          <div class="fg col-6">
            <label>Số điện thoại <span class="req">*</span></label>
            <input type="tel" name="phone" placeholder="0901 234 567" required>
          </div>
          <div class="fg col-6">
            <label>Email</label>
            <input type="email" name="email" placeholder="email@example.com">
          </div>
          <div class="fg col-3">
            <label>Giới tính</label>
            <select name="gender">
              <option value="male">Nam</option>
              <option value="female">Nữ</option>
              <option value="other">Khác</option>
            </select>
          </div>
          <div class="fg col-3">
            <label>Năm sinh</label>
            <input type="number" name="birth_year" placeholder="1990" min="1930" max="<?= date('Y') ?>">
          </div>
          <div class="fg col-8">
            <label>Địa chỉ</label>
            <input type="text" name="address" placeholder="Số nhà, đường...">
          </div>
          <div class="fg col-4">
            <label>Tỉnh/Thành</label>
            <input type="text" name="city" placeholder="VD: Ninh Bình">
          </div>
        </div>
      </div>

      <!-- SECTION 2: Nguồn & Phân công -->
      <div class="form-section">
        <div class="form-section-title">
          <svg viewBox="0 0 24 24">
            <path d="M3 11l19-9-9 19-2-8-8-2z" />
          </svg>
          Nguồn & Phân công
        </div>
        <div class="form-grid">
          <div class="fg col-6">
            <label>Nguồn khách</label>

            <!-- Tabs chọn nhóm nguồn -->
            <div style="display:flex;gap:6px;margin-bottom:8px;">
              <button type="button" onclick="switchSourceTab('local')" id="tabBtn-local"
                class="src-tab src-tab-active" style="flex:1;">
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 11l19-9-9 19-2-8-8-2z" />
                </svg>
                Nguồn cũ (<?= count($sourcesLocal) + count($sourcesSynced) ?>)
              </button>
              <button type="button" onclick="switchSourceTab('sql')" id="tabBtn-sql"
                class="src-tab" style="flex:1;">
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                  <circle cx="9" cy="7" r="4" />
                </svg>
                Nguồn SQL (<?= count($groupedSql) ?> loại)
              </button>
            </div>

            <!-- Panel LOCAL -->
            <div id="srcPanel-local">
              <select name="source_id" class="src-select">
                <option value="">-- Chọn nguồn --</option>
                <?php if (!empty($sourcesLocal)): ?>
                  <optgroup label="Nguồn Marketing">
                    <?php foreach ($sourcesLocal as $s): ?>
                      <option value="<?= $s['id'] ?>"><?= Helper::e($s['name']) ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endif; ?>
                <?php if (!empty($sourcesSynced)): ?>
                  <optgroup label="Nguồn đã đồng bộ từ SQL Server">
                    <?php foreach ($sourcesSynced as $s): ?>
                      <option value="<?= $s['id'] ?>"><?= Helper::e($s['name']) ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endif; ?>
              </select>
            </div>

            <!-- Panel SQL Server -->
            <div id="srcPanel-sql" style="display:none;">
              <select name="source_sql_id" class="src-select">
                <option value="">-- Chọn nguồn khám đoàn --</option>
                <?php foreach ($groupedSql as $category => $items): ?>
                  <optgroup label="📁 <?= Helper::e($category) ?> (<?= count($items) ?>)">
                    <?php foreach ($items as $s): ?>
                      <option value="<?= $s['ID'] ?>">
                        <?= Helper::e($s['Ten']) ?><?= $s['MaCty'] ? ' [' . Helper::e($s['MaCty']) . ']' : '' ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endforeach; ?>
              </select>
              <div style="font-size:11.5px;color:#64748b;margin-top:6px;display:flex;align-items:center;gap:5px;">
                <span style="color:#22d3ee;">●</span>
                Nguồn sẽ <b style="color:#22d3ee;">tự động đồng bộ</b> vào CRM khi lưu KH
              </div>
            </div>
          </div>
          <div class="fg col-6">
            <label>Nhân viên phụ trách</label>
            <select name="assigned_to">
              <option value="">Tự nhận</option>
              <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>"><?= Helper::e($u['full_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- SECTION 3: Nhu cầu & Trạng thái -->
      <div class="form-section">
        <div class="form-section-title">
          <svg viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
          Nhu cầu & Trạng thái
        </div>
        <div class="form-grid">
          <div class="fg col-6">
            <label>Bệnh lý quan tâm</label>
            <select name="interest">
              <option value="">-- Chọn bệnh lý --</option>
              <?php
              global $pdo;
              $diseases = $pdo->query("SELECT name FROM diseases WHERE is_active = 1 ORDER BY sort_order, name")->fetchAll();
              foreach ($diseases as $d): ?>
                <option value="<?= Helper::e($d['name']) ?>"><?= Helper::e($d['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="fg col-6">
            <label>Trạng thái</label>
            <select name="status">
              <?php foreach (['new', 'contacted', 'appointment', 'consulted', 'surgery', 'done', 'lost'] as $s): ?>
                <option value="<?= $s ?>"><?= Helper::statusLabel($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="fg col-6">
            <label>Giá trị dự kiến (VNĐ)</label>
            <input type="number" name="estimated_value" value="0" step="100000" placeholder="0">
          </div>
          <div class="fg col-6">
            <label>Follow-up tiếp theo</label>
            <input type="datetime-local" name="next_followup_at" id="next_followup" step="60">
            <small style="font-size:11px;color:#64748b;margin-top:2px;">Để trống nếu chưa cần nhắc</small>
          </div>
          <div class="fg col-12">
            <label>Ghi chú</label>
            <textarea name="note" placeholder="Thông tin thêm về khách hàng..."></textarea>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <a href="?c=customer" class="btn btn-ghost">Hủy</a>
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24">
            <polyline points="20 6 9 17 4 12" />
          </svg>
          Lưu khách hàng
        </button>
      </div>
    </div>
  </div>

</form>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form.form-card');
    const fup = document.getElementById('next_followup');

    if (!form || !fup) return;

    // Khi user rời field, tự động clear nếu giá trị không hợp lệ
    fup.addEventListener('blur', function() {
      if (this.value && this.validity && !this.validity.valid) {
        this.value = '';
      }
    });

    // Trước khi submit, kiểm tra
    form.addEventListener('submit', function(e) {
      // Validate các field required thủ công để tránh lỗi khó hiểu
      const name = form.querySelector('input[name="full_name"]');
      const phone = form.querySelector('input[name="phone"]');

      if (name && !name.value.trim()) {
        e.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Thiếu thông tin',
          text: 'Vui lòng nhập họ tên khách hàng',
          background: '#0d1526',
          color: '#e2e8f0',
          confirmButtonColor: '#06b6d4'
        });
        name.focus();
        return false;
      }

      if (phone && !phone.value.trim()) {
        e.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Thiếu thông tin',
          text: 'Vui lòng nhập số điện thoại',
          background: '#0d1526',
          color: '#e2e8f0',
          confirmButtonColor: '#06b6d4'
        });
        phone.focus();
        return false;
      }

      // Kiểm tra follow-up nếu có giá trị
      if (fup.value) {
        const re = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/;
        if (!re.test(fup.value)) {
          e.preventDefault();
          Swal.fire({
            icon: 'warning',
            title: 'Ngày giờ không hợp lệ',
            text: 'Vui lòng chọn đầy đủ ngày VÀ giờ, hoặc để trống',
            background: '#0d1526',
            color: '#e2e8f0',
            confirmButtonColor: '#06b6d4'
          });
          fup.focus();
          return false;
        }
      }
    });
  });
</script>
<style>
.src-tab {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 8px 12px;
  border-radius: 9px;
  background: rgba(15,23,42,.6);
  border: 1.5px solid rgba(148,163,184,.15);
  color: #94a3b8;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all .2s;
  font-family: inherit;
}
.src-tab:hover {
  color: #e2e8f0;
  border-color: rgba(34,211,238,.3);
}
.src-tab.src-tab-active {
  background: linear-gradient(135deg, rgba(6,182,212,.15), rgba(139,92,246,.1));
  border-color: rgba(34,211,238,.5);
  color: #fff;
}
.src-select {
  width: 100%;
  height: 44px;
  padding: 0 40px 0 14px;
  background: rgba(15,23,42,.7);
  border: 1.5px solid rgba(148,163,184,.18);
  border-radius: 11px;
  color: #f1f5f9;
  font-size: 13.5px;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 14px center;
}
.src-select:focus {
  border-color: rgba(34,211,238,.6);
  box-shadow: 0 0 0 3px rgba(6,182,212,.15);
  outline: none;
}
.src-select optgroup {
  background: #1a2332;
  color: #22d3ee;
  font-weight: 700;
  font-size: 12px;
  padding: 8px;
}
.src-select option {
  background: #131b2e;
  color: #e2e8f0;
  padding: 6px;
}
</style>

<script>
function switchSourceTab(tab) {
  // Buttons
  document.querySelectorAll('.src-tab').forEach(b => b.classList.remove('src-tab-active'));
  document.getElementById('tabBtn-' + tab).classList.add('src-tab-active');
  
  // Panels
  document.getElementById('srcPanel-local').style.display = tab === 'local' ? 'block' : 'none';
  document.getElementById('srcPanel-sql').style.display = tab === 'sql' ? 'block' : 'none';
  
  // Clear select đối diện để tránh gửi cả 2
  if (tab === 'local') {
    document.querySelector('#srcPanel-sql select').value = '';
  } else {
    document.querySelector('#srcPanel-local select').value = '';
  }
  
  // Animate
  const panel = document.getElementById('srcPanel-' + tab);
  panel.style.opacity = '0';
  panel.style.transform = 'translateY(10px)';
  requestAnimationFrame(() => {
    panel.style.transition = 'all .3s cubic-bezier(.16,1,.3,1)';
    panel.style.opacity = '1';
    panel.style.transform = 'translateY(0)';
  });
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>