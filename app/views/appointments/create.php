<?php
$pageTitle = 'Đặt lịch hẹn';
$activePage = 'appointment';
include __DIR__ . '/../layouts/header.php';
?>

<form method="post" action="?c=appointment&a=store" class="form-card">
  <div class="card gsap-fade">
    <div class="card-head">
      <h3><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Đặt lịch hẹn mới</h3>
    </div>
    <div class="card-body">
      <div class="form-grid">
        <div class="fg col-6">
          <label>Khách hàng <span class="req">*</span></label>
          <select name="customer_id" required>
            <option value="">-- Chọn khách hàng --</option>
            <?php foreach ($customers as $c): ?>
              <option value="<?= $c['id'] ?>"><?= Helper::e($c['full_name']) ?> (<?= Helper::e($c['phone']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="fg col-6">
          <label>Tiêu đề <span class="req">*</span></label>
          <input type="text" name="title" placeholder="VD: Khám tư vấn Lasik" required>
        </div>
        <div class="fg col-6">
          <label>Thời gian <span class="req">*</span></label>
          <input type="datetime-local" name="scheduled_at" step="60" required>
        </div>
        <div class="fg col-6">
          <label>Bác sĩ</label>
          <input type="text" name="doctor" placeholder="BS. Trần Minh...">
        </div>
        <div class="fg col-6">
          <label>Trạng thái</label>
          <select name="status">
            <option value="pending">Chờ xác nhận</option>
            <option value="confirmed">Đã xác nhận</option>
          </select>
        </div>
        <div class="fg col-12">
          <label>Ghi chú</label>
          <textarea name="note" placeholder="Ghi chú thêm..."></textarea>
        </div>
      </div>
      <div class="form-actions">
        <a href="?c=appointment" class="btn btn-ghost">Hủy</a>
        <button class="btn btn-primary ripple">
          <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          Lưu lịch hẹn
        </button>
      </div>
    </div>
  </div>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>