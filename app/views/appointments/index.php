<?php
$pageTitle = 'Lịch hẹn';
$activePage = 'appointment';
include __DIR__ . '/../layouts/header.php';

$statusMap = [
  'pending'   => ['Chờ xác nhận', 'badge-appointment', '#fbbf24'],
  'confirmed' => ['Đã xác nhận', 'badge-contacted', '#60a5fa'],
  'done'      => ['Đã khám', 'badge-surgery', '#34d399'],
  'cancelled' => ['Đã hủy', 'badge-lost', '#f87171'],
  'no_show'   => ['Không đến', 'badge-lost', '#f87171'],
];

// Build calendar
$firstDay = strtotime($month . '-01');
$daysInMonth = (int)date('t', $firstDay);
$startWeekday = (int)date('N', $firstDay); // 1=Mon, 7=Sun
$today = date('Y-m-d');
$prevMonth = date('Y-m', strtotime($month . '-01 -1 month'));
$nextMonth = date('Y-m', strtotime($month . '-01 +1 month'));
?>

<!-- HEADER STATS -->
<div class="stats mb-5">
  <div class="stat c-cyan gsap-fade">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
    <div class="stat-val" data-count="<?= $stats['total'] ?>">0</div>
    <div class="stat-lbl">Tổng lịch hẹn</div>
    <div class="stat-hint"><strong>+<?= $stats['today'] ?></strong> hôm nay</div>
  </div>
  <div class="stat c-amber gsap-fade" style="animation-delay:.06s">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    <div class="stat-val" data-count="<?= $stats['pending'] + $stats['confirmed'] ?>">0</div>
    <div class="stat-lbl">Sắp tới / Chờ</div>
    <div class="stat-hint"><?= $stats['pending'] ?> chờ · <?= $stats['confirmed'] ?> xác nhận</div>
  </div>
  <div class="stat c-emerald gsap-fade" style="animation-delay:.12s">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
    <div class="stat-val" data-count="<?= $stats['done'] ?>">0</div>
    <div class="stat-lbl">Đã khám</div>
    <div class="stat-hint">Hoàn thành</div>
  </div>
  <div class="stat c-purple gsap-fade" style="animation-delay:.18s">
    <div class="stat-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
    <div class="stat-val" data-count="<?= $stats['cancelled'] ?>">0</div>
    <div class="stat-lbl">Hủy / Không đến</div>
    <div class="stat-hint">Cần theo dõi</div>
  </div>
</div>

<!-- TABS: Calendar / List -->
<div class="tabs mb-4">
  <div class="tab active" onclick="switchView(this,'calendar')">
    <svg viewBox="0 0 24 24" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;margin-right:6px;stroke:currentColor;fill:none;stroke-width:2;">
      <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/>
    </svg>
    Lịch tháng
  </div>
  <div class="tab" onclick="switchView(this,'list')">
    <svg viewBox="0 0 24 24" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;margin-right:6px;stroke:currentColor;fill:none;stroke-width:2;">
      <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
      <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
    </svg>
    Danh sách
  </div>
</div>

<!-- ═══════════ VIEW 1: CALENDAR ═══════════ -->
<div id="view-calendar">
  <div class="card mb-4 gsap-fade">
    <div class="card-head" style="border-bottom:0;padding-bottom:0;">
      <div style="display:flex;align-items:center;gap:12px;">
        <a href="?c=appointment&month=<?= $prevMonth ?>" class="btn btn-ghost btn-icon" data-tip="Tháng trước">
          <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <h3 style="font-family:'Space Grotesk';font-size:18px;color:#fff;min-width:200px;text-align:center;">
          Tháng <?= date('m/Y', $firstDay) ?>
        </h3>
        <a href="?c=appointment&month=<?= $nextMonth ?>" class="btn btn-ghost btn-icon" data-tip="Tháng sau">
          <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
        <a href="?c=appointment" class="btn btn-ghost btn-sm">Hôm nay</a>
      </div>
      <div style="display:flex;gap:6px;align-items:center;">
        <span class="quick-pill"><span style="width:8px;height:8px;border-radius:50%;background:#fbbf24;"></span>Chờ</span>
        <span class="quick-pill"><span style="width:8px;height:8px;border-radius:50%;background:#60a5fa;"></span>Xác nhận</span>
        <span class="quick-pill"><span style="width:8px;height:8px;border-radius:50%;background:#34d399;"></span>Xong</span>
      </div>
    </div>
    <div class="card-body">
      <div class="calendar">
        <!-- Weekdays -->
        <div class="cal-head">
          <?php foreach (['T2','T3','T4','T5','T6','T7','CN'] as $i => $d): ?>
            <div class="cal-head-cell <?= $i >= 5 ? 'weekend' : '' ?>"><?= $d ?></div>
          <?php endforeach; ?>
        </div>
        
        <!-- Days -->
        <div class="cal-body">
          <?php 
          // Fill trước ngày 1
          for ($i = 1; $i < $startWeekday; $i++) {
              echo '<div class="cal-cell cal-empty"></div>';
          }
          
          for ($day = 1; $day <= $daysInMonth; $day++):
              $dateStr = sprintf('%s-%02d', $month, $day);
              $isToday = $dateStr === $today;
              $isPast = strtotime($dateStr) < strtotime($today);
              $dayApts = $aptsByDate[$dateStr] ?? [];
              $weekday = (int)date('N', strtotime($dateStr));
              $isWeekend = $weekday >= 6;
          ?>
            <div class="cal-cell <?= $isToday ? 'today' : '' ?> <?= $isPast ? 'past' : '' ?> <?= $isWeekend ? 'weekend' : '' ?>"
                 data-date="<?= $dateStr ?>"
                 onclick="openDayDetail('<?= $dateStr ?>')">
              <div class="cal-day-num"><?= $day ?></div>
              <div class="cal-apts">
                <?php foreach (array_slice($dayApts, 0, 3) as $apt): 
                  $st = $statusMap[$apt['status']] ?? $statusMap['pending'];
                ?>
                  <div class="cal-apt" style="background:<?= $st[2] ?>20;border-left:3px solid <?= $st[2] ?>;" 
                       onclick="event.stopPropagation(); editApt(<?= $apt['id'] ?>)">
                    <span style="color:<?= $st[2] ?>;font-weight:600;">
                      <?= date('H:i', strtotime($apt['scheduled_at'])) ?>
                    </span>
                    <span class="cal-apt-name"><?= Helper::e(mb_substr($apt['customer_name'], 0, 15)) ?></span>
                  </div>
                <?php endforeach; ?>
                <?php if (count($dayApts) > 3): ?>
                  <div class="cal-more">+<?= count($dayApts) - 3 ?> lịch khác</div>
                <?php endif; ?>
              </div>
            </div>
          <?php endfor; 
          // Fill sau ngày cuối
          $fill = 7 - (($startWeekday + $daysInMonth - 1) % 7) - 1;
          if ($fill > 0 && $fill < 7) {
              for ($i = 0; $i < $fill; $i++) echo '<div class="cal-cell cal-empty"></div>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════ VIEW 2: LIST ═══════════ -->
<div id="view-list" style="display:none;">
  
  <!-- FILTERS -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="get" style="display:flex;gap:10px;flex-wrap:wrap;align-items:end;">
        <input type="hidden" name="c" value="appointment">
        <input type="hidden" name="view" value="list">
        
        <div class="fg" style="min-width:160px;">
          <label>Trạng thái</label>
          <select name="status">
            <option value="">Tất cả</option>
            <?php foreach ($statusMap as $k => $v): ?>
              <option value="<?= $k ?>" <?= $filterStatus === $k ? 'selected' : '' ?>><?= $v[0] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="fg" style="min-width:160px;">
          <label>Ngày</label>
          <input type="date" name="date" value="<?= $filterDate ?>">
        </div>
        
        <?php if (Auth::isManager()): ?>
        <div class="fg" style="min-width:180px;">
          <label>Nhân viên</label>
          <select name="user">
            <option value="">Tất cả</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= $u['id'] ?>" <?= $filterUser == $u['id'] ? 'selected' : '' ?>><?= Helper::e($u['full_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php endif; ?>
        
        <div class="fg" style="min-width:180px;">
          <label>Bác sĩ</label>
          <select name="doctor">
            <option value="">Tất cả</option>
            <?php foreach ($doctors as $d): ?>
              <option value="<?= Helper::e($d['doctor']) ?>" <?= $filterDoctor === $d['doctor'] ? 'selected' : '' ?>><?= Helper::e($d['doctor']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <button type="submit" class="btn btn-primary" style="height:44px;">
          <svg viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          Lọc
        </button>
        
        <?php if ($filterStatus || $filterDate || $filterUser || $filterDoctor): ?>
          <a href="?c=appointment&view=list" class="btn btn-ghost" style="height:44px;">Xóa lọc</a>
        <?php endif; ?>
        
        <a href="?c=appointment&a=create" class="btn btn-primary" style="height:44px;margin-left:auto;">
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Đặt lịch mới
        </a>
      </form>
    </div>
  </div>
  
  <!-- TABLE -->
  <div class="card gsap-fade">
    <div class="card-head">
      <h3>
        <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/></svg>
        Danh sách lịch hẹn
        <span class="badge badge-new" style="margin-left:8px;"><span class="bdot"></span><?= count($apts) ?></span>
      </h3>
    </div>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th style="width:130px;">Thời gian</th>
            <th>Khách hàng</th>
            <th>Tiêu đề</th>
            <th>Bác sĩ</th>
            <th>NV</th>
            <th style="width:140px;text-align:center;">Trạng thái</th>
            <th style="width:130px;text-align:right;">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($apts)): ?>
            <tr><td colspan="7">
              <div class="empty empty-anim">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <p>Không có lịch hẹn nào</p>
              </div>
            </td></tr>
          <?php else: foreach ($apts as $i => $a): 
            $ts = strtotime($a['scheduled_at']);
            $isPast = $ts < time();
            $st = $statusMap[$a['status']] ?? $statusMap['pending'];
          ?>
            <tr class="gsap-fade" style="animation-delay:<?= $i * .02 ?>s;opacity:<?= $isPast ? '.7' : '1' ?>;">
              <td>
                <div style="font-family:'Space Grotesk';font-weight:700;color:#22d3ee;font-size:15px;">
                  <?= date('H:i', $ts) ?>
                </div>
                <div style="font-size:11.5px;color:#64748b;margin-top:2px;">
                  <?= date('d/m/Y', $ts) ?>
                </div>
              </td>
              <td>
                <div style="display:flex;align-items:center;gap:10px;">
                  <div class="ava ava-sm"><?= mb_substr($a['customer_name'], 0, 1) ?></div>
                  <div>
                    <div style="font-weight:600;color:#fff;"><?= Helper::e($a['customer_name']) ?></div>
                    <div style="font-size:11.5px;color:#64748b;"><?= Helper::e($a['customer_phone']) ?></div>
                  </div>
                </div>
              </td>
              <td>
                <div style="font-weight:500;color:#e2e8f0;font-size:13.5px;"><?= Helper::e($a['title'] ?: 'Khám bệnh') ?></div>
                <?php if ($a['interest']): ?>
                  <div style="font-size:11.5px;color:#64748b;margin-top:2px;"><?= Helper::e($a['interest']) ?></div>
                <?php endif; ?>
              </td>
              <td style="font-size:13px;color:#cbd5e1;"><?= Helper::e($a['doctor'] ?: '—') ?></td>
              <td style="font-size:12.5px;color:#94a3b8;"><?= Helper::e($a['user_name'] ?: '—') ?></td>
              <td style="text-align:center;">
                <select onchange="changeStatus(<?= $a['id'] ?>, this.value)" 
                        class="badge <?= $st[1] ?>" 
                        style="cursor:pointer;border:1px solid;padding:5px 10px;background:<?= $st[2] ?>20;color:<?= $st[2] ?>;border-color:<?= $st[2] ?>40;font-weight:700;font-size:11px;border-radius:8px;">
                  <?php foreach ($statusMap as $k => $v): ?>
                    <option value="<?= $k ?>" <?= $a['status'] === $k ? 'selected' : '' ?> style="background:#131b2e;color:#fff;"><?= $v[0] ?></option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td style="text-align:right;">
                <div style="display:flex;gap:6px;justify-content:flex-end;">
                  <button class="btn btn-ghost btn-icon" onclick="editApt(<?= $a['id'] ?>)" data-tip="Sửa">
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button class="btn btn-ghost btn-icon" style="color:#f87171;" onclick="deleteApt(<?= $a['id'] ?>)" data-tip="Xóa">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ═══════════ MODAL: Chi tiết ngày ═══════════ -->
<div class="modal-ov" id="dayModal">
  <div class="modal" style="max-width:700px;">
    <div class="modal-head">
      <h3 id="dayModalTitle">Chi tiết ngày</h3>
      <button class="modal-close" onclick="closeModal('dayModal')">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body" id="dayModalBody"></div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal('dayModal')">Đóng</button>
      <button class="btn btn-primary" onclick="quickAddApt()">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Thêm lịch hẹn
      </button>
    </div>
  </div>
</div>

<!-- ═══════════ MODAL: Thêm/Sửa lịch hẹn ═══════════ -->
<div class="modal-ov" id="aptModal">
  <form class="modal" style="max-width:600px;" id="aptForm">
    <input type="hidden" name="id" id="aptId">
    <div class="modal-head">
      <h3 id="aptModalTitle">Thêm lịch hẹn mới</h3>
      <button type="button" class="modal-close" onclick="closeModal('aptModal')">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="fg col-12">
          <label>Khách hàng <span class="req">*</span></label>
          <select name="customer_id" id="aptCustomer" required>
            <option value="">-- Chọn khách hàng --</option>
            <?php foreach ($customers as $c): ?>
              <option value="<?= $c['id'] ?>"><?= Helper::e($c['full_name']) ?> (<?= Helper::e($c['phone']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="fg col-12">
          <label>Tiêu đề <span class="req">*</span></label>
          <input type="text" name="title" id="aptTitle" placeholder="VD: Khám tư vấn Lasik" required>
        </div>
        <div class="fg col-6">
          <label>Thời gian <span class="req">*</span></label>
          <input type="datetime-local" name="scheduled_at" id="aptScheduled" step="60" required>
        </div>
        <div class="fg col-6">
          <label>Bác sĩ</label>
          <input type="text" name="doctor" id="aptDoctor" placeholder="VD: BS. Trần Minh" list="doctorList">
          <datalist id="doctorList">
            <?php foreach ($doctors as $d): ?>
              <option value="<?= Helper::e($d['doctor']) ?>">
            <?php endforeach; ?>
          </datalist>
        </div>
        <div class="fg col-6">
          <label>Trạng thái</label>
          <select name="status" id="aptStatus">
            <?php foreach ($statusMap as $k => $v): ?>
              <option value="<?= $k ?>"><?= $v[0] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="fg col-6">
          <label>Nhắc trước</label>
          <input type="datetime-local" name="reminder_at" id="aptReminder" step="60">
        </div>
        <div class="fg col-12">
          <label>Ghi chú</label>
          <textarea name="note" id="aptNote" placeholder="Ghi chú thêm..." style="min-height:70px;"></textarea>
        </div>
      </div>
      
      <!-- Conflict warning -->
      <div id="conflictWarn" style="display:none;margin-top:16px;padding:12px;border-radius:10px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:#fbbf24;font-size:13px;">
        <strong>⚠️ Cảnh báo trùng lịch:</strong>
        <div id="conflictList" style="margin-top:6px;"></div>
        <button type="button" class="btn btn-sm" style="margin-top:8px;background:rgba(245,158,11,.2);color:#fbbf24;border:1px solid rgba(245,158,11,.4);" onclick="forceSave()">
          Vẫn lưu
        </button>
      </div>
    </div>
    <div class="modal-foot">
      <button type="button" class="btn btn-ghost" onclick="closeModal('aptModal')">Hủy</button>
      <button type="submit" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        Lưu lịch hẹn
      </button>
    </div>
  </form>
</div>

<style>
/* ═══ CALENDAR ═══ */
.calendar {
  background: rgba(10,14,26,.4);
  border-radius: 14px;
  overflow: hidden;
  border: 1px solid rgba(148,163,184,.1);
}
.cal-head {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  background: rgba(30,41,59,.6);
  border-bottom: 1px solid rgba(148,163,184,.1);
}
.cal-head-cell {
  padding: 12px;
  text-align: center;
  font-size: 11px;
  font-weight: 700;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: .08em;
}
.cal-head-cell.weekend { color: #f87171; }

.cal-body {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
}
.cal-cell {
  min-height: 110px;
  padding: 8px;
  border-right: 1px solid rgba(148,163,184,.06);
  border-bottom: 1px solid rgba(148,163,184,.06);
  cursor: pointer;
  transition: background .15s;
  position: relative;
}
.cal-cell:hover { background: rgba(148,163,184,.04); }
.cal-cell.cal-empty { background: rgba(10,14,26,.2); cursor: default; }
.cal-cell.weekend { background: rgba(239,68,68,.02); }
.cal-cell.past { opacity: .5; }
.cal-cell.today { background: rgba(6,182,212,.08); }
.cal-cell.today::before {
  content: '';
  position: absolute;
  inset: 0;
  border: 2px solid rgba(34,211,238,.5);
  border-radius: 4px;
  pointer-events: none;
}
.cal-day-num {
  font-family: 'Space Grotesk';
  font-size: 14px;
  font-weight: 700;
  color: #94a3b8;
  margin-bottom: 6px;
}
.cal-cell.today .cal-day-num { color: #22d3ee; }
.cal-apts { display: flex; flex-direction: column; gap: 3px; }
.cal-apt {
  padding: 3px 6px;
  border-radius: 5px;
  font-size: 10.5px;
  cursor: pointer;
  transition: all .15s;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.cal-apt:hover { transform: translateX(2px); }
.cal-apt-name { color: #cbd5e1; margin-left: 4px; }
.cal-more {
  font-size: 10px;
  color: #64748b;
  padding-left: 6px;
  font-style: italic;
}

@media (max-width: 768px) {
  .cal-cell { min-height: 80px; padding: 4px; }
  .cal-day-num { font-size: 12px; }
  .cal-apt { font-size: 9px; }
}
</style>

<script>
// ═══ TABS ═══
function switchView(el, view) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('view-calendar').style.display = view === 'calendar' ? 'block' : 'none';
  document.getElementById('view-list').style.display = view === 'list' ? 'block' : 'none';
}

// Auto open list if URL has view=list
<?php if (($_GET['view'] ?? '') === 'list'): ?>
document.addEventListener('DOMContentLoaded', () => {
  const listTab = document.querySelectorAll('.tab')[1];
  switchView(listTab, 'list');
});
<?php endif; ?>

// ═══ MODAL ═══
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
document.querySelectorAll('.modal-ov').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') document.querySelectorAll('.modal-ov.show').forEach(m => m.classList.remove('show'));
});

// ═══ DAY DETAIL ═══
let currentDayDate = null;
function openDayDetail(dateStr) {
  currentDayDate = dateStr;
  const d = new Date(dateStr + 'T00:00:00');
  const dayNames = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
  document.getElementById('dayModalTitle').textContent = 
    `${dayNames[d.getDay()]}, ${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`;
  
  // Show loading
  document.getElementById('dayModalBody').innerHTML = '<div style="text-align:center;padding:30px;color:#64748b;">Đang tải...</div>';
  document.getElementById('dayModal').classList.add('show');
  
  fetch('?c=appointment&a=byDate&date=' + dateStr)
    .then(r => r.json())
    .then(apts => {
      if (!apts.length) {
        document.getElementById('dayModalBody').innerHTML = `
          <div class="empty" style="padding:30px;">
            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/></svg>
            <p>Không có lịch hẹn nào trong ngày này</p>
          </div>`;
        return;
      }
      
      const statusMap = <?= json_encode($statusMap) ?>;
      const html = apts.map(a => {
        const st = statusMap[a.status] || statusMap.pending;
        const time = a.scheduled_at.substring(11, 16);
        return `
          <div class="apt-card" style="margin-bottom:10px;" onclick="editApt(${a.id})">
            <div class="apt-date">
              <div class="day">${time.split(':')[0]}</div>
              <div class="month">:${time.split(':')[1]}</div>
            </div>
            <div style="flex:1;min-width:0;">
              <div style="font-weight:600;color:#fff;">${a.customer_name}</div>
              <div style="font-size:12px;color:#94a3b8;margin-top:2px;">${a.title || 'Khám bệnh'}</div>
              <div style="font-size:11.5px;color:#64748b;margin-top:4px;">
                ${a.customer_phone} ${a.doctor ? ' · ' + a.doctor : ''}
              </div>
            </div>
            <span class="badge" style="background:${st[2]}20;color:${st[2]};border-color:${st[2]}40;">${st[0]}</span>
          </div>
        `;
      }).join('');
      
      document.getElementById('dayModalBody').innerHTML = html;
    })
    .catch(e => {
      document.getElementById('dayModalBody').innerHTML = '<div style="color:#f87171;padding:20px;">Lỗi tải dữ liệu</div>';
    });
}

// ═══ CREATE / EDIT ═══
function openAddApt(prefillDate = null) {
  document.getElementById('aptForm').reset();
  document.getElementById('aptId').value = '';
  document.getElementById('aptModalTitle').textContent = 'Thêm lịch hẹn mới';
  document.getElementById('aptForm').action = '?c=appointment&a=store';
  document.getElementById('conflictWarn').style.display = 'none';
  
  if (prefillDate) {
    document.getElementById('aptScheduled').value = prefillDate + 'T09:00';
  }
  
  document.getElementById('aptModal').classList.add('show');
}

function quickAddApt() {
  closeModal('dayModal');
  setTimeout(() => openAddApt(currentDayDate), 200);
}

function editApt(id) {
  fetch('?c=appointment&a=get&id=' + id)
    .then(r => r.json())
    .then(a => {
      if (!a) { alert('Không tìm thấy'); return; }
      
      document.getElementById('aptId').value = a.id;
      document.getElementById('aptModalTitle').textContent = 'Chỉnh sửa lịch hẹn';
      document.getElementById('aptCustomer').value = a.customer_id;
      document.getElementById('aptTitle').value = a.title || '';
      
      // Convert datetime to local format
      const dt = a.scheduled_at.replace(' ', 'T').substring(0, 16);
      document.getElementById('aptScheduled').value = dt;
      
      document.getElementById('aptDoctor').value = a.doctor || '';
      document.getElementById('aptStatus').value = a.status;
      document.getElementById('aptNote').value = a.note || '';
      
      if (a.reminder_at) {
        document.getElementById('aptReminder').value = a.reminder_at.replace(' ', 'T').substring(0, 16);
      }
      
      document.getElementById('aptForm').action = '?c=appointment&a=update';
      document.getElementById('conflictWarn').style.display = 'none';
      closeModal('dayModal');
      document.getElementById('aptModal').classList.add('show');
    });
}

// ═══ SUBMIT FORM ═══
document.getElementById('aptForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  
  const res = await fetch(this.action, { method: 'POST', body: formData });
  const data = await res.json();
  
  // Conflict warning
  if (data.conflict && data.conflict.length > 0) {
    const list = data.conflict.map(c => 
      `<div style="padding:4px 0;">• ${c.scheduled_at.substring(11,16)} - ${c.customer_name} (${c.title})</div>`
    ).join('');
    document.getElementById('conflictList').innerHTML = list;
    document.getElementById('conflictWarn').style.display = 'block';
    return;
  }
  
  if (data.ok) {
    closeModal('aptModal');
    Swal.fire({
      toast: true, position: 'top-end', icon: 'success',
      title: 'Đã lưu lịch hẹn!',
      showConfirmButton: false, timer: 1500,
      background: 'rgba(26,35,50,.95)', color: '#e2e8f0'
    });
    if (window.confetti) window.confetti();
    setTimeout(() => location.reload(), 800);
  } else {
    Swal.fire({ icon: 'error', title: 'Lỗi', text: data.error || 'Không lưu được', background: '#1a2332', color: '#e2e8f0' });
  }
});

function forceSave() {
  const form = document.getElementById('aptForm');
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'force';
  input.value = '1';
  form.appendChild(input);
  form.dispatchEvent(new Event('submit'));
}

// ═══ CHANGE STATUS ═══
async function changeStatus(id, status) {
  const res = await fetch('?c=appointment&a=updateStatus', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: `id=${id}&status=${status}`
  });
  const data = await res.json();
  
  if (data.ok) {
    Swal.fire({
      toast: true, position: 'top-end', icon: 'success',
      title: 'Đã cập nhật trạng thái',
      showConfirmButton: false, timer: 1200,
      background: 'rgba(26,35,50,.95)', color: '#e2e8f0'
    });
  }
}

// ═══ DELETE ═══
async function deleteApt(id) {
  const r = await Swal.fire({
    title: 'Xóa lịch hẹn?',
    text: 'Hành động này không thể hoàn tác',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Xóa',
    cancelButtonText: 'Hủy',
    confirmButtonColor: '#ef4444',
    background: '#1a2332', color: '#e2e8f0'
  });
  if (!r.isConfirmed) return;
  
  const res = await fetch('?c=appointment&a=delete', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'id=' + id
  });
  const data = await res.json();
  
  if (data.ok) {
    Swal.fire({
      toast: true, position: 'top-end', icon: 'success',
      title: 'Đã xóa',
      showConfirmButton: false, timer: 1200,
      background: 'rgba(26,35,50,.95)', color: '#e2e8f0'
    });
    setTimeout(() => location.reload(), 800);
  }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>