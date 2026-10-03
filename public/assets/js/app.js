// Sidebar toggle mobile
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
  document.querySelector('.sidebar').classList.toggle('show');
});

// Auto-hide alerts
document.querySelectorAll('.alert').forEach(a => {
  setTimeout(() => { 
    a.style.transition = 'opacity .5s, transform .5s';
    a.style.opacity = 0; 
    a.style.transform = 'translateY(-20px)';
    setTimeout(()=>a.remove(), 500);
  }, 4000);
});

// Global search
document.getElementById('globalSearch')?.addEventListener('keydown', e => {
  if (e.key === 'Enter' && e.target.value.trim()) {
    location.href = `?c=customer&q=${encodeURIComponent(e.target.value.trim())}`;
  }
});

// Counter animation
document.querySelectorAll('.stat-value').forEach(el => {
  const text = el.textContent.trim();
  const num = parseInt(text.replace(/[^\d]/g, ''));
  if (!num || isNaN(num)) return;
  const suffix = text.replace(/[\d.,]/g, '');
  let cur = 0;
  const step = num / 40;
  const timer = setInterval(() => {
    cur += step;
    if (cur >= num) { cur = num; clearInterval(timer); }
    el.textContent = Math.floor(cur).toLocaleString('vi-VN') + suffix;
  }, 20);
});

// Confirm delete
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', e => {
    if (!confirm(el.dataset.confirm)) e.preventDefault();
  });
});