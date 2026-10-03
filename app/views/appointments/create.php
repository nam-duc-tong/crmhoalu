<?php
$pageTitle = 'Đặt lịch hẹn';
$activePage = 'appointment';
include __DIR__ . '/../layouts/header.php';
?>

<div class="flex justify-between items-center mb-4" style="flex-wrap:wrap;gap:12px;">
  <div>
    <p style="color:#94a3b8;font-size:13px;">Tạo lịch hẹn mới cho khách hàng</p>
  </div>
  <a href="?c=appointment" class="btn btn-ghost">
    <svg viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Quay lại
  </a>
</div>

<form method="post" action="?c=appointment&a=store" class="form-card" id="aptCreateForm">
  <div class="card gsap-fade">
    <div class="card-head">
      <h3>
        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Thông tin lịch hẹn
      </h3>
    </div>

    <div class="card-body">

      <!-- SECTION 1: Khách hàng -->
      <div class="form-section">
        <div class="form-section-title">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          Khách hàng
        </div>
        <div class="form-grid">
          <div class="fg col-12">
            <label>Chọn khách hàng <span class="req">*</span></label>
            <select name="customer_id" required>
              <option value="">-- Chọn khách hàng --</option>
              <?php foreach ($customers as $c): ?>
                <option value="<?= $c['id'] ?>">
                  <?= Helper::e($c['full_name']) ?> · <?= Helper::e($c['phone']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div style="font-size:11.5px;color:#64748b;margin-top:5px;">
              Chưa có khách hàng? <a href="?c=customer&a=create" style="color:#22d3ee;">Thêm KH mới</a>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION 2: Chi tiết lịch hẹn -->
      <div class="form-section">
        <div class="form-section-title">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Chi tiết lịch hẹn
        </div>
        <div class="form-grid">
          <div class="fg col-8">
            <label>Tiêu đề <span class="req">*</span></label>
            <input type="text" name="title" placeholder="VD: Khám tư vấn Lasik, Tái khám..." required autofocus>
          </div>
          <div class="fg col-4">
            <label>Trạng thái</label>
            <select name="status">
              <option value="pending">Chờ xác nhận</option>
              <option value="confirmed">Đã xác nhận</option>
              <option value="done">Đã khám</option>
              <option value="cancelled">Đã hủy</option>
            </select>
          </div>

          <div class="fg col-6">
            <label>Thời gian <span class="req">*</span></label>
            <input type="datetime-local" name="scheduled_at" id="scheduled" required step="60">
            <div style="font-size:11.5px;color:#64748b;margin-top:5px;">
              Để trống giây, tự động lấy :00
            </div>
          </div>

          <div class="fg col-6">
            <label>Bác sĩ phụ trách</label>
            <input type="text" name="doctor" list="doctorList" placeholder="VD: BS. Trần Minh">
            <datalist id="doctorList">
              <option value="BS. Trần Minh">
              <option value="BS. Lê Hoa">
              <option value="BS. Nguyễn Hùng">
              <option value="BS. Phạm Lan">
            </datalist>
          </div>

          <div class="fg col-6">
            <label>Nhắc nhở trước (tùy chọn)</label>
            <input type="datetime-local" name="reminder_at" step="60">
            <div style="font-size:11.5px;color:#64748b;margin-top:5px;">
              Hệ thống sẽ nhắc nhân viên trước giờ này
            </div>
          </div>

          <div class="fg col-6">
            <label>Thời lượng dự kiến</label>
            <select name="duration_minutes">
              <option value="15">15 phút</option>
              <option value="30" selected>30 phút</option>
              <option value="45">45 phút</option>
              <option value="60">1 giờ</option>
              <option value="90">1.5 giờ</option>
              <option value="120">2 giờ</option>
            </select>
          </div>

          <div class="fg col-12">
            <label>Ghi chú</label>
            <textarea name="note" placeholder="Ghi chú về buổi khám, yêu cầu đặc biệt..."></textarea>
          </div>
        </div>
      </div>

      <!-- ACTIONS -->
      <div class="form-actions">
        <a href="?c=appointment" class="btn btn-ghost">Hủy</a>
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          Đặt lịch hẹn
        </button>
      </div>

    </div>
  </div>
</form>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<script>
// Auto set datetime default = ngày mai 9h
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('scheduled');
  if (!input.value) {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(9, 0, 0, 0);
    const yyyy = tomorrow.getFullYear();
    const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
    const dd = String(tomorrow.getDate()).padStart(2, '0');
    const hh = String(tomorrow.getHours()).padStart(2, '0');
    const mi = String(tomorrow.getMinutes()).padStart(2, '0');
    input.value = `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
  }
});

// Submit via fetch → hiển thị SweetAlert đẹp hơn
document.getElementById('aptCreateForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = this.querySelector('button[type="submit"]');
  const originalHTML = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<svg viewBox="0 0 24 24" style="animation:spin 1s linear infinite;width:16px;height:16px;"><circle cx="12" cy="12" r="10" opacity="0.25" fill="none" stroke="currentColor" stroke-width="3"/><path d="M12 2a10 10 0 0 1 10 10" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg> Đang lưu...';
  
  try {
    const formData = new FormData(this);
    const res = await fetch(this.action, { method: 'POST', body: formData });
    const data = await res.json();
    
    // Có conflict → hỏi user
    if (data.conflict && data.conflict.length > 0) {
      const list = data.conflict.map(c => 
        `<div style="padding:4px 0;text-align:left;">• <b>${c.scheduled_at.substring(11,16)}</b> - ${c.customer_name} <span style="color:#94a3b8;">(${c.title})</span></div>`
      ).join('');
      
      const r = await Swal.fire({
        title: 'Cảnh báo trùng lịch!',
        html: `<div style="font-size:13px;color:#94a3b8;margin-bottom:10px;">Có ${data.conflict.length} lịch hẹn gần giờ này:</div>${list}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Vẫn đặt lịch',
        cancelButtonText: 'Hủy',
        confirmButtonColor: '#f59e0b',
        background: '#1a2332', color: '#e2e8f0'
      });
      
      if (r.isConfirmed) {
        formData.append('force', '1');
        const res2 = await fetch(this.action, { method: 'POST', body: formData });
        const data2 = await res2.json();
        if (data2.ok) {
          handleSuccess(data2.id);
        } else {
          handleError(data2.error);
        }
      } else {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
      }
      return;
    }
    
    if (data.ok) {
      handleSuccess(data.id);
    } else {
      handleError(data.error);
    }
  } catch (err) {
    handleError(err.message);
  }
  
  function handleSuccess(id) {
    Swal.fire({
      icon: 'success',
      title: 'Đã đặt lịch hẹn!',
      text: 'Chuyển đến trang chi tiết...',
      timer: 1500,
      showConfirmButton: false,
      background: '#1a2332', color: '#e2e8f0'
    }).then(() => {
      location.href = '?c=appointment';
    });
    if (window.confetti) window.confetti();
  }
  
  function handleError(msg) {
    Swal.fire({
      icon: 'error',
      title: 'Không lưu được',
      text: msg || 'Vui lòng kiểm tra lại thông tin',
      background: '#1a2332', color: '#e2e8f0'
    });
    btn.disabled = false;
    btn.innerHTML = originalHTML;
  }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>