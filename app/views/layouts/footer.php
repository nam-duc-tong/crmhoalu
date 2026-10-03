    </div><!-- /.body -->
  </main>

  <!-- FAB -->
  <div class="fab" onclick="toggleFab()">
    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
  </div>
  <div class="fab-menu" id="fabMenu">
    <a href="?c=customer&a=create" class="fab-item">
      <div class="fbi-icon" style="background:linear-gradient(135deg,#06b6d4,#3b82f6)">
        <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
      </div>
      Thêm khách hàng
    </a>
    <a href="?c=appointment&a=create" class="fab-item">
      <div class="fbi-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      </div>
      Đặt lịch hẹn
    </a>
    <a href="?c=ticket&a=create" class="fab-item">
      <div class="fbi-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">
        <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </div>
      Tạo vé CSKH
    </a>
  </div>
</div>

<!-- COMMAND PALETTE -->
<div class="cmdk-overlay" id="cmdk" onclick="if(event.target===this)closeCmdk()">
  <div class="cmdk">
    <input type="text" class="cmdk-input" id="cmdkInput" placeholder="Tìm trang, hành động..." autocomplete="off">
    <div class="cmdk-list">
      <div class="cmdk-section">Trang</div>
      <a href="?c=dashboard" class="cmdk-item"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/></svg>Dashboard</a>
      <a href="?c=customer" class="cmdk-item"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Khách hàng</a>
      <a href="?c=pipeline" class="cmdk-item"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>Pipeline</a>
      <a href="?c=appointment" class="cmdk-item"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Lịch hẹn</a>
      <a href="?c=ticket" class="cmdk-item"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Vé CSKH</a>
      <a href="?c=report" class="cmdk-item"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Báo cáo</a>
      <a href="?c=setting" class="cmdk-item"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>Cài đặt</a>
      <div class="cmdk-section">Hành động</div>
      <a href="?c=customer&a=create" class="cmdk-item"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Thêm khách hàng</a>
      <a href="?c=appointment&a=create" class="cmdk-item"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Đặt lịch hẹn</a>
      <a href="?c=auth&a=logout" class="cmdk-item"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/></svg>Đăng xuất</a>
    </div>
  </div>
</div>

<!-- NOTIFICATION PANEL -->
<div class="noti-panel" id="notiPanel">
  <div class="noti-head">
    <h3>Thông báo</h3>
    <button class="btn btn-ghost btn-sm" onclick="markAllRead()">Đã đọc</button>
  </div>
  <div class="noti-list">
    <div class="noti-item unread">
      <div class="noti-icon"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
      <div class="noti-body">
        <strong>Ca mổ thành công</strong>
        <small>Khách Nguyễn Văn An đã hoàn tất phẫu thuật</small>
        <div class="time">15 phút trước</div>
      </div>
    </div>
    <div class="noti-item unread">
      <div class="noti-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/></svg></div>
      <div class="noti-body">
        <strong>Lịch hẹn sắp tới</strong>
        <small>Trần Thị Bình có lịch khám lúc 9:00 sáng mai</small>
        <div class="time">2 giờ trước</div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<script>
gsap.registerPlugin();

// Entrance animations
gsap.utils.toArray('.gsap-fade').forEach((el, i) => {
  gsap.to(el, { opacity: 1, y: 0, duration: .6, delay: i * .04, ease: 'power3.out' });
});
gsap.utils.toArray('.gsap-slide-left').forEach((el, i) => {
  gsap.to(el, { opacity: 1, x: 0, duration: .5, delay: i * .05, ease: 'power3.out' });
});
// gsap.from('.sb-link', { x: -20, opacity: 0, duration: .4, stagger: .04, delay: .2, ease: 'power2.out' });

// Cursor glow
const cg = document.getElementById('cursorGlow');
let mx = 0, my = 0, gx = 0, gy = 0;
document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
(function loop() {
  gx += (mx - gx) * .12; gy += (my - gy) * .12;
  if (cg) cg.style.transform = `translate(${gx}px, ${gy}px) translate(-50%,-50%)`;
  requestAnimationFrame(loop);
})();

// Number counter
document.querySelectorAll('[data-count]').forEach(el => {
  const target = parseInt(el.dataset.count) || 0;
  if (!target) return;
  const suffix = el.dataset.suffix || '';
  const duration = 1500;
  const start = performance.now();
  function tick(now) {
    const p = Math.min((now - start) / duration, 1);
    const eased = 1 - Math.pow(1 - p, 3);
    el.textContent = Math.floor(target * eased).toLocaleString('vi-VN') + suffix;
    if (p < 1) requestAnimationFrame(tick);
  }
  requestAnimationFrame(tick);
});

// FAB
function toggleFab() {
  document.getElementById('fabMenu').classList.toggle('show');
}

// Command Palette
function openCmdk() {
  document.getElementById('cmdk').classList.add('show');
  setTimeout(() => document.getElementById('cmdkInput').focus(), 100);
}
function closeCmdk() { document.getElementById('cmdk').classList.remove('show'); }
document.addEventListener('keydown', e => {
  if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); openCmdk(); }
  if (e.key === 'Escape') { closeCmdk(); document.getElementById('notiPanel')?.classList.remove('show'); }
});
document.getElementById('cmdkInput')?.addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('.cmdk-item').forEach(item => {
    item.style.display = item.textContent.toLowerCase().includes(q) ? 'flex' : 'none';
  });
});

// Notifications
function toggleNoti() { document.getElementById('notiPanel').classList.toggle('show'); }
function markAllRead() {
  document.querySelectorAll('.noti-item.unread').forEach(el => el.classList.remove('unread'));
  Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Đã đánh dấu', showConfirmButton: false, timer: 1200, background: 'rgba(15,23,42,.95)', color: '#e2e8f0' });
}
document.addEventListener('click', e => {
  const panel = document.getElementById('notiPanel');
  const btn = e.target.closest('.top-btn');
  if (panel && !panel.contains(e.target) && !btn) panel.classList.remove('show');
});

// Ripple
document.querySelectorAll('.btn-primary, .btn-ghost').forEach(btn => {
  btn.addEventListener('click', e => {
    const r = btn.getBoundingClientRect();
    const span = document.createElement('span');
    const size = Math.max(r.width, r.height);
    span.style.cssText = `position:absolute;left:${e.clientX-r.left-size/2}px;top:${e.clientY-r.top-size/2}px;width:${size}px;height:${size}px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.5),transparent 70%);transform:scale(0);pointer-events:none;animation:rippleExpand .7s ease-out;`;
    btn.style.position = 'relative';
    btn.style.overflow = 'hidden';
    btn.appendChild(span);
    setTimeout(() => span.remove(), 700);
  });
});
const rs = document.createElement('style');
rs.textContent = '@keyframes rippleExpand{to{transform:scale(2.5);opacity:0}}';
document.head.appendChild(rs);

// Flash
<?php if ($flash): ?>
Swal.fire({
  toast: true, position: 'top-end',
  icon: '<?= $flash['type'] === 'danger' ? 'error' : $flash['type'] ?>',
  title: <?= json_encode($flash['msg']) ?>,
  showConfirmButton: false, timer: 3200, timerProgressBar: true,
  background: 'rgba(15,23,42,.95)', color: '#e2e8f0'
});
<?php endif; ?>
</script>

</body>
</html>