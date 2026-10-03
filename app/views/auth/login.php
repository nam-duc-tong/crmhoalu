<?php $flash = Helper::getFlash(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Đăng nhập — <?= APP_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: 'Inter', sans-serif;
  background: #050816;
  color: #e2e8f0;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  overflow-x: hidden;
}
.bg {
  position: fixed; inset: 0; z-index: 0;
  background:
    radial-gradient(ellipse 60% 50% at 15% 20%, rgba(6,182,212,0.25), transparent 55%),
    radial-gradient(ellipse 55% 50% at 85% 25%, rgba(139,92,246,0.28), transparent 55%),
    radial-gradient(ellipse 70% 60% at 50% 90%, rgba(236,72,153,0.18), transparent 55%),
    #050816;
  background-size: 200% 200%;
  animation: bgMove 20s ease infinite;
}
@keyframes bgMove { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }

.grid {
  position: fixed; inset: 0; z-index: 1; pointer-events: none;
  background-image: 
    linear-gradient(rgba(148,163,184,0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(148,163,184,0.05) 1px, transparent 1px);
  background-size: 60px 60px;
  -webkit-mask-image: radial-gradient(ellipse 70% 70% at center, #000 20%, transparent 80%);
  mask-image: radial-gradient(ellipse 70% 70% at center, #000 20%, transparent 80%);
}

.orb { position: fixed; border-radius: 50%; filter: blur(80px); opacity: 0.5; z-index: 1; pointer-events: none; transition: transform 0.4s ease-out; }
.orb-1 { width: 420px; height: 420px; background: #06b6d4; top: -120px; left: -100px; }
.orb-2 { width: 380px; height: 380px; background: #8b5cf6; bottom: -120px; right: -100px; }
.orb-3 { width: 300px; height: 300px; background: #ec4899; top: 45%; left: 55%; opacity: 0.3; }

.card {
  position: relative; z-index: 10;
  width: 100%; max-width: 440px;
  background: rgba(15,23,42,0.75);
  backdrop-filter: blur(30px) saturate(180%);
  -webkit-backdrop-filter: blur(30px) saturate(180%);
  border-radius: 26px;
  padding: 42px 38px 34px;
  box-shadow: 0 40px 100px rgba(0,0,0,0.6), 0 0 0 1px rgba(148,163,184,0.1) inset;
  animation: cardIn 0.8s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes cardIn { from { opacity: 0; transform: translateY(40px) scale(0.96); } to { opacity: 1; transform: translateY(0) scale(1); } }

.card::before {
  content: ''; position: absolute; inset: -1.5px; border-radius: 26px; padding: 1.5px;
  background: linear-gradient(135deg, #06b6d4, #8b5cf6, #ec4899, #06b6d4);
  background-size: 300% 300%;
  -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor; mask-composite: exclude;
  animation: borderFlow 5s linear infinite;
  opacity: 0.7; pointer-events: none;
}
@keyframes borderFlow { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }

.logo-wrap { display: flex; justify-content: center; margin-bottom: 20px; }
.logo {
  width: 76px; height: 76px; border-radius: 22px;
  background: linear-gradient(135deg, #06b6d4, #3b82f6, #8b5cf6);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 20px 40px rgba(6,182,212,0.4);
  animation: logoPulse 3s ease-in-out infinite;
}
@keyframes logoPulse {
  0%,100% { box-shadow: 0 20px 40px rgba(6,182,212,0.4); }
  50% { box-shadow: 0 20px 55px rgba(139,92,246,0.6), 0 0 50px rgba(6,182,212,0.45); }
}
.logo svg { width: 38px; height: 38px; stroke: #fff; fill: none; stroke-width: 2; }

.title { font-family: 'Space Grotesk', sans-serif; font-size: 24px; font-weight: 700; text-align: center; color: #fff; margin-bottom: 6px; letter-spacing: -0.02em; }
.accent { background: linear-gradient(90deg, #22d3ee, #60a5fa, #a78bfa); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; color: transparent; }
.subtitle { text-align: center; font-size: 13px; color: #94a3b8; margin-bottom: 28px; }

.alert { display: flex; align-items: center; gap: 10px; padding: 12px 14px; margin-bottom: 20px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 12px; color: #fca5a5; font-size: 13px; }
.alert svg { flex-shrink: 0; }

.field { margin-bottom: 16px; }
.field-label { display: block; font-size: 12px; font-weight: 600; color: #cbd5e1; margin-bottom: 7px; }
.input-wrap { position: relative; }
.input-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #64748b; pointer-events: none; transition: color 0.25s; }
.input-icon svg { width: 100%; height: 100%; }
.input {
  display: block; width: 100%; height: 48px; padding: 0 16px 0 44px;
  background: rgba(15,23,42,0.7); border: 1.5px solid rgba(148,163,184,0.18); border-radius: 12px;
  color: #f1f5f9; font-size: 14px; font-family: inherit; outline: none; transition: all 0.25s;
  -webkit-appearance: none; appearance: none;
}
.input::placeholder { color: #64748b; }
.input:focus { border-color: rgba(34,211,238,0.6); background: rgba(15,23,42,0.95); box-shadow: 0 0 0 4px rgba(6,182,212,0.12); }
.input-wrap:focus-within .input-icon { color: #22d3ee; }
.input:-webkit-autofill, .input:-webkit-autofill:focus {
  -webkit-text-fill-color: #f1f5f9;
  -webkit-box-shadow: 0 0 0 1000px rgba(15,23,42,0.95) inset;
  transition: background-color 5000s;
  caret-color: #f1f5f9;
}

.toggle-pass { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 34px; height: 34px; border: none; border-radius: 8px; background: transparent; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.toggle-pass:hover { color: #22d3ee; background: rgba(34,211,238,0.08); }
.toggle-pass svg { width: 16px; height: 16px; }

.form-row { display: flex; align-items: center; justify-content: space-between; margin: 6px 0 22px; font-size: 13px; }
.checkbox { display: flex; align-items: center; gap: 8px; color: #94a3b8; cursor: pointer; user-select: none; }
.checkbox input { appearance: none; -webkit-appearance: none; width: 16px; height: 16px; border: 1.5px solid #475569; border-radius: 5px; cursor: pointer; position: relative; background: rgba(15,23,42,0.5); }
.checkbox input:checked { background: linear-gradient(135deg, #06b6d4, #3b82f6); border-color: transparent; }
.checkbox input:checked::after { content: ''; position: absolute; left: 5px; top: 1px; width: 4px; height: 9px; border: solid #fff; border-width: 0 2px 2px 0; transform: rotate(45deg); }
.forgot { color: #22d3ee; font-weight: 500; }

.btn-submit {
  display: flex; align-items: center; justify-content: center; gap: 10px;
  width: 100%; height: 52px; min-height: 52px;
  padding: 0 20px; border: none; border-radius: 13px;
  background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 50%, #8b5cf6 100%);
  background-size: 200% 200%;
  color: #fff !important; font-size: 15px; font-weight: 700; font-family: inherit; letter-spacing: 0.02em;
  cursor: pointer; overflow: hidden; position: relative;
  transition: transform 0.25s, box-shadow 0.25s;
  box-shadow: 0 10px 30px rgba(6,182,212,0.3);
  animation: btnFlow 4s ease infinite;
}
@keyframes btnFlow { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 20px 40px rgba(6,182,212,0.5), 0 0 60px rgba(139,92,246,0.25); }
.btn-submit:active { transform: translateY(0) scale(0.99); }
.btn-submit:disabled { opacity: 0.85; cursor: not-allowed; transform: none; }
.btn-submit svg { width: 18px; height: 18px; flex-shrink: 0; }

.hint { display: flex; gap: 10px; padding: 13px 14px; margin-top: 20px; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.12); border-radius: 12px; font-size: 12px; color: #94a3b8; line-height: 1.6; }
.hint > svg { flex-shrink: 0; width: 15px; height: 15px; color: #22d3ee; margin-top: 1px; }
.hint strong { color: #22d3ee; font-family: ui-monospace, monospace; }
.hint-label { color: #cbd5e1; font-weight: 600; display: block; margin-bottom: 3px; }

.footer-text { text-align: center; font-size: 11px; color: #475569; margin-top: 20px; }

.shake { animation: shakeAnim 0.4s; }
@keyframes shakeAnim { 0%,100%{transform:translateX(0)} 20%,60%{transform:translateX(-8px)} 40%,80%{transform:translateX(8px)} }
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 480px) {
  .card { padding: 34px 22px 26px; border-radius: 22px; }
  .title { font-size: 20px; }
}
</style>
</head>
<body>

<div class="bg"></div>
<div class="grid"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<form method="post" class="card" id="loginForm">
  
  <div class="logo-wrap">
    <div class="logo">
      <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
        <circle cx="12" cy="12" r="3"/>
      </svg>
    </div>
  </div>

  <h1 class="title">Bệnh viện Mắt <span class="accent">Hoa Lư</span></h1>
  <p class="subtitle">Đăng nhập vào hệ thống quản lý khách hàng</p>

  <?php if ($flash): ?>
  <div class="alert">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
    </svg>
    <span><?= Helper::e($flash['msg']) ?></span>
  </div>
  <?php endif; ?>

  <div class="field">
    <label class="field-label" for="email">Email</label>
    <div class="input-wrap">
      <span class="input-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
          <polyline points="22,6 12,13 2,6"/>
        </svg>
      </span>
      <input type="email" name="email" id="email" class="input" placeholder="ten@hoalu.vn" required autofocus autocomplete="username" value="admin@hoalu.vn">
    </div>
  </div>

  <div class="field">
    <label class="field-label" for="password">Mật khẩu</label>
    <div class="input-wrap">
      <span class="input-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
      </span>
      <input type="password" name="password" id="password" class="input" placeholder="Nhập mật khẩu" required autocomplete="current-password" style="padding-right: 46px;" value="123456">
      <button type="button" class="toggle-pass" id="togglePass" aria-label="Hiện/ẩn mật khẩu">
        <svg id="eyeOff" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
          <line x1="1" y1="1" x2="23" y2="23"/>
        </svg>
        <svg id="eyeOn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
      </button>
    </div>
  </div>

  <div class="form-row">
    <label class="checkbox">
      <input type="checkbox" name="remember">
      <span>Ghi nhớ đăng nhập</span>
    </label>
    <a href="#" class="forgot">Quên mật khẩu?</a>
  </div>

  <button type="submit" class="btn-submit" id="submitBtn">
    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
      <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
    </svg>
    <span>Đăng nhập</span>
  </button>

  <div class="hint">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
    </svg>
    <div>
      <span class="hint-label">Tài khoản demo</span>
      Email: <strong>admin@hoalu.vn</strong><br>
      Mật khẩu: <strong>123456</strong>
    </div>
  </div>

  <div class="footer-text">© <?= date('Y') ?> Bệnh viện Mắt Hoa Lư · CRM v3.0</div>
</form>

<script>
document.getElementById('togglePass').addEventListener('click', function() {
  var input = document.getElementById('password');
  var eyeOff = document.getElementById('eyeOff');
  var eyeOn = document.getElementById('eyeOn');
  var showing = input.type === 'text';
  input.type = showing ? 'password' : 'text';
  eyeOff.style.display = showing ? 'block' : 'none';
  eyeOn.style.display  = showing ? 'none'  : 'block';
});

<?php if ($flash): ?>
window.addEventListener('load', function() {
  var card = document.querySelector('.card');
  setTimeout(function() {
    card.classList.add('shake');
    setTimeout(function() { card.classList.remove('shake'); }, 500);
  }, 400);
});
<?php endif; ?>

document.getElementById('loginForm').addEventListener('submit', function() {
  var btn = document.getElementById('submitBtn');
  btn.disabled = true;
  btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" style="animation:spin 1s linear infinite;width:18px;height:18px"><circle cx="12" cy="12" r="10" opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/></svg><span>Đang đăng nhập...</span>';
});

var orbs = document.querySelectorAll('.orb');
document.addEventListener('mousemove', function(e) {
  var x = (e.clientX / window.innerWidth - 0.5) * 30;
  var y = (e.clientY / window.innerHeight - 0.5) * 30;
  orbs[0].style.transform = 'translate(' + x + 'px,' + y + 'px)';
  orbs[1].style.transform = 'translate(' + (-x) + 'px,' + (-y) + 'px)';
  orbs[2].style.transform = 'translate(' + (x * 0.6) + 'px,' + (y * 0.6) + 'px)';
});
</script>
</body>
</html>