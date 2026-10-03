<?php
$pageTitle = 'Cài đặt hệ thống';
$activePage = 'setting';
include __DIR__ . '/../layouts/header.php';

$typeLabels = [
  'facebook' => 'Facebook', 'google' => 'Google', 'tiktok' => 'TikTok',
  'walkin'   => 'Khách vãng lai', 'referral' => 'Giới thiệu',
  'hotline'  => 'Hotline', 'other' => 'Khác',
];

$categoryLabels = [
  'refraction' => ['Khúc xạ', '#06b6d4'],
  'cataract'   => ['Thủy tinh thể', '#8b5cf6'],
  'glaucoma'   => ['Glaucoma', '#ec4899'],
  'retina'     => ['Võng mạc', '#f59e0b'],
  'strabismus' => ['Mắt lác', '#10b981'],
  'general'    => ['Tổng quát', '#64748b'],
  'other'      => ['Khác', '#94a3b8'],
];
?>

<!-- ═══ TABS ═══ -->
<div class="tabs">
  <div class="tab active" onclick="switchTab(this, 'sources')">
    <svg viewBox="0 0 24 24" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;margin-right:6px;stroke:currentColor;fill:none;stroke-width:2;">
      <path d="M3 11l19-9-9 19-2-8-8-2z"/>
    </svg>
    Nguồn khách
  </div>
  <div class="tab" onclick="switchTab(this, 'diseases')">
    <svg viewBox="0 0 24 24" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;margin-right:6px;stroke:currentColor;fill:none;stroke-width:2;">
      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
    </svg>
    Nguồn bệnh
  </div>
  <div class="tab" onclick="switchTab(this, 'users')">
    <svg viewBox="0 0 24 24" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;margin-right:6px;stroke:currentColor;fill:none;stroke-width:2;">
      <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
    </svg>
    Người dùng
  </div>
  <div class="tab" onclick="switchTab(this, 'system')">
    <svg viewBox="0 0 24 24" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;margin-right:6px;stroke:currentColor;fill:none;stroke-width:2;">
      <circle cx="12" cy="12" r="3"/>
      <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
    </svg>
    Hệ thống
  </div>
</div>

<!-- ═══════════════════════════════════════════ -->
<!-- TAB 1: NGUỒN KHÁCH -->
<!-- ═══════════════════════════════════════════ -->
<div class="tab-content" id="tab-sources">
  <div class="card gsap-fade">
    <div class="card-head">
      <h3>
        <svg viewBox="0 0 24 24"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>
        Danh sách nguồn khách hàng
        <span class="badge badge-new" style="margin-left:8px;"><span class="bdot"></span><?= count($sources) ?> nguồn</span>
      </h3>
      <button class="btn btn-primary btn-sm" onclick="openSourceModal()">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Thêm nguồn
      </button>
    </div>

    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th style="width:60px;">Icon</th>
            <th>Tên nguồn</th>
            <th>Loại</th>
            <th style="text-align:center;">Số KH</th>
            <th style="text-align:right;">Doanh thu</th>
            <th style="text-align:center;">Trạng thái</th>
            <th style="width:120px;text-align:right;">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($sources as $s): 
            $stat = $sourceStatsMap[$s['id']] ?? ['total' => 0, 'revenue' => 0];
          ?>
          <tr data-source-id="<?= $s['id'] ?>">
            <td>
              <div style="width:38px;height:38px;border-radius:11px;background:<?= Helper::e($s['color']) ?>20;border:1px solid <?= Helper::e($s['color']) ?>50;display:flex;align-items:center;justify-content:center;">
                <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:<?= Helper::e($s['color']) ?>;fill:none;stroke-width:2;">
                  <?php if ($s['icon'] === 'facebook'): ?>
                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                  <?php elseif ($s['icon'] === 'search'): ?>
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                  <?php elseif ($s['icon'] === 'music'): ?>
                    <path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
                  <?php elseif ($s['icon'] === 'phone'): ?>
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                  <?php elseif ($s['icon'] === 'footprints'): ?>
                    <path d="M4 16v-2.38C4 11.5 2.97 10.5 3 8c.03-2.72 1.49-6 4.5-6C9.37 2 10 3.8 10 5.5c0 3.11-2 5.66-2 8.68V16a2 2 0 1 1-4 0Z"/><path d="M20 20v-2.38c0-2.12 1.03-3.12 1-5.62-.03-2.72-1.49-6-4.5-6C14.63 6 14 7.8 14 9.5c0 3.11 2 5.66 2 8.68V20a2 2 0 1 0 4 0Z"/>
                  <?php elseif ($s['icon'] === 'users'): ?>
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                  <?php else: ?>
                    <circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                  <?php endif; ?>
                </svg>
              </div>
            </td>
            <td>
              <div style="font-weight:600;color:#fff;"><?= Helper::e($s['name']) ?></div>
              <?php if (!empty($s['description'])): ?>
                <div style="font-size:11.5px;color:#64748b;margin-top:2px;"><?= Helper::e($s['description']) ?></div>
              <?php endif; ?>
            </td>
            <td><span class="tag"><?= $typeLabels[$s['type']] ?? $s['type'] ?></span></td>
            <td style="text-align:center;">
              <strong style="color:#22d3ee;font-family:'Space Grotesk';font-size:15px;"><?= $stat['total'] ?></strong>
            </td>
            <td style="text-align:right;font-family:'Space Grotesk';font-weight:600;color:#34d399;">
              <?= Helper::money($stat['revenue']) ?>
            </td>
            <td style="text-align:center;">
              <button class="badge <?= $s['is_active'] ? 'badge-surgery' : 'badge-lost' ?>" 
                      style="cursor:pointer;border:1px solid;" 
                      onclick="toggleSource(<?= $s['id'] ?>, this)"
                      data-tip="Click để <?= $s['is_active'] ? 'tắt' : 'bật' ?>">
                <span class="bdot"></span><?= $s['is_active'] ? 'Hoạt động' : 'Tắt' ?>
              </button>
            </td>
            <td style="text-align:right;">
              <div style="display:flex;gap:6px;justify-content:flex-end;">
                <button class="btn btn-ghost btn-icon" onclick='openSourceModal(<?= json_encode($s, JSON_HEX_APOS) ?>)' data-tip="Sửa">
                  <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button class="btn btn-ghost btn-icon" style="color:#f87171;" onclick="deleteSource(<?= $s['id'] ?>, '<?= Helper::e($s['name']) ?>')" data-tip="Xóa">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════ -->
<!-- TAB 2: NGUỒN BỆNH -->
<!-- ═══════════════════════════════════════════ -->
<div class="tab-content" id="tab-diseases" style="display:none;">
  <div class="card gsap-fade">
    <div class="card-head">
      <h3>
        <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        Danh mục nguồn bệnh
        <span class="badge badge-consulted" style="margin-left:8px;"><span class="bdot"></span><?= count($diseases) ?> bệnh lý</span>
      </h3>
      <button class="btn btn-primary btn-sm" onclick="openDiseaseModal()">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Thêm bệnh lý
      </button>
    </div>

    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th style="width:60px;">Icon</th>
            <th>Mã</th>
            <th>Tên bệnh lý</th>
            <th>Danh mục</th>
            <th style="text-align:right;">Giá từ</th>
            <th style="text-align:right;">Giá đến</th>
            <th style="text-align:center;">Trạng thái</th>
            <th style="width:120px;text-align:right;">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($diseases as $d): 
            $cat = $categoryLabels[$d['category']] ?? $categoryLabels['other'];
          ?>
          <tr>
            <td>
              <div style="width:38px;height:38px;border-radius:11px;background:<?= Helper::e($d['color']) ?>20;border:1px solid <?= Helper::e($d['color']) ?>50;display:flex;align-items:center;justify-content:center;">
                <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke:<?= Helper::e($d['color']) ?>;fill:none;stroke-width:2;">
                  <?php if ($d['icon'] === 'glasses'): ?>
                    <circle cx="6" cy="15" r="4"/><circle cx="18" cy="15" r="4"/><path d="M14 15a2 2 0 0 0-4 0"/><path d="M2.5 13 5 7c.7-1.3 1.4-2 3-2"/><path d="M21.5 13 19 7c-.7-1.3-1.5-2-3-2"/>
                  <?php elseif ($d['icon'] === 'sparkles'): ?>
                    <path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/>
                  <?php elseif ($d['icon'] === 'aperture'): ?>
                    <circle cx="12" cy="12" r="10"/><line x1="14.31" y1="8" x2="20.05" y2="17.94"/><line x1="9.69" y1="8" x2="21.17" y2="8"/><line x1="7.38" y1="12" x2="13.12" y2="2.06"/><line x1="9.69" y1="16" x2="3.95" y2="6.06"/><line x1="14.31" y1="16" x2="2.83" y2="16"/><line x1="16.62" y1="12" x2="10.88" y2="21.94"/>
                  <?php elseif ($d['icon'] === 'droplet'): ?>
                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                  <?php elseif ($d['icon'] === 'droplets'): ?>
                    <path d="M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.71-3.19S7.29 6.75 7 5.3c-.29 1.45-1.14 2.84-2.29 3.76S3 11.1 3 12.25c0 2.22 1.8 4.05 4 4.05z"/><path d="M12.56 6.6A10.97 10.97 0 0 0 14 3.02c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a6.98 6.98 0 0 1-11.91 4.97"/>
                  <?php elseif ($d['icon'] === 'eye'): ?>
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                  <?php elseif ($d['icon'] === 'move'): ?>
                    <polyline points="5 9 2 12 5 15"/><polyline points="9 5 12 2 15 5"/><polyline points="15 19 12 22 9 19"/><polyline points="19 9 22 12 19 15"/><line x1="2" y1="12" x2="22" y2="12"/><line x1="12" y1="2" x2="12" y2="22"/>
                  <?php elseif ($d['icon'] === 'clipboard'): ?>
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                  <?php else: ?>
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                  <?php endif; ?>
                </svg>
              </div>
            </td>
            <td><span class="chip"><?= Helper::e($d['code']) ?></span></td>
            <td>
              <div style="font-weight:600;color:#fff;"><?= Helper::e($d['name']) ?></div>
              <?php if (!empty($d['description'])): ?>
                <div style="font-size:11.5px;color:#64748b;margin-top:2px;"><?= Helper::e($d['description']) ?></div>
              <?php endif; ?>
            </td>
            <td>
              <span class="badge" style="background:<?= $cat[1] ?>20;color:<?= $cat[1] ?>;border-color:<?= $cat[1] ?>50;">
                <?= $cat[0] ?>
              </span>
            </td>
            <td style="text-align:right;color:#94a3b8;font-size:13px;"><?= Helper::moneyFull($d['price_from']) ?></td>
            <td style="text-align:right;color:#34d399;font-weight:600;font-size:13px;"><?= Helper::moneyFull($d['price_to']) ?></td>
            <td style="text-align:center;">
              <button class="badge <?= $d['is_active'] ? 'badge-surgery' : 'badge-lost' ?>" 
                      style="cursor:pointer;border:1px solid;" 
                      onclick="toggleDisease(<?= $d['id'] ?>, this)">
                <span class="bdot"></span><?= $d['is_active'] ? 'Hoạt động' : 'Tắt' ?>
              </button>
            </td>
            <td style="text-align:right;">
              <div style="display:flex;gap:6px;justify-content:flex-end;">
                <button class="btn btn-ghost btn-icon" onclick='openDiseaseModal(<?= json_encode($d, JSON_HEX_APOS) ?>)' data-tip="Sửa">
                  <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button class="btn btn-ghost btn-icon" style="color:#f87171;" onclick="deleteDisease(<?= $d['id'] ?>, '<?= Helper::e($d['name']) ?>')" data-tip="Xóa">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ═══ TAB 3: USERS ═══ -->
<div class="tab-content" id="tab-users" style="display:none;">
  <div class="card gsap-fade">
    <div class="card-head">
      <h3><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Người dùng hệ thống</h3>
    </div>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead><tr><th>Nhân viên</th><th>Email</th><th>Vai trò</th><th>Nhóm</th><th style="text-align:right;">KPI</th></tr></thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><div style="display:flex;align-items:center;gap:10px;"><div class="ava ava-sm"><?= mb_substr($u['full_name'], 0, 1) ?></div><strong style="color:#fff;"><?= Helper::e($u['full_name']) ?></strong></div></td>
              <td style="color:#94a3b8;"><?= Helper::e($u['email']) ?></td>
              <td><span class="tag"><?= $u['role'] ?></span></td>
              <td><?= Helper::e($u['team'] ?: '—') ?></td>
              <td style="text-align:right;font-family:'Space Grotesk';color:#34d399;font-weight:600;"><?= Helper::money($u['kpi_target']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ═══ TAB 4: SYSTEM ═══ -->
<div class="tab-content" id="tab-system" style="display:none;">
  <div class="stats">
    <div class="stat c-cyan"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><div class="stat-val" data-count="<?= $stats['customers'] ?>">0</div><div class="stat-lbl">Khách hàng</div></div>
    <div class="stat c-purple"><div class="stat-icon"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div><div class="stat-val" data-count="<?= $stats['activities'] ?>">0</div><div class="stat-lbl">Hoạt động</div></div>
    <div class="stat c-amber"><div class="stat-icon"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div><div class="stat-val" data-count="<?= $stats['appointments'] ?>">0</div><div class="stat-lbl">Lịch hẹn</div></div>
    <div class="stat c-emerald"><div class="stat-icon"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div><div class="stat-val" data-count="<?= $stats['tickets'] ?>">0</div><div class="stat-lbl">Vé CSKH</div></div>
  </div>
</div>

<!-- ═══════════════════════════════════════════ -->
<!-- MODAL: SOURCE -->
<!-- ═══════════════════════════════════════════ -->
<div class="modal-ov" id="sourceModal">
  <form method="post" action="?c=setting&a=sourceStore" class="modal" style="max-width:600px;" id="sourceForm">
    <input type="hidden" name="id" id="sourceId">
    <div class="modal-head">
      <h3 id="sourceModalTitle">Thêm nguồn khách mới</h3>
      <button type="button" class="modal-close" onclick="closeModal('sourceModal')">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="fg col-8">
          <label>Tên nguồn <span class="req">*</span></label>
          <input type="text" name="name" id="sourceName" placeholder="VD: Facebook Ads" required>
        </div>
        <div class="fg col-4">
          <label>Loại</label>
          <select name="type" id="sourceType">
            <option value="facebook">Facebook</option>
            <option value="google">Google</option>
            <option value="tiktok">TikTok</option>
            <option value="hotline">Hotline</option>
            <option value="walkin">Khách vãng lai</option>
            <option value="referral">Giới thiệu</option>
            <option value="other">Khác</option>
          </select>
        </div>
        <div class="fg col-8">
          <label>Icon</label>
          <select name="icon" id="sourceIcon">
            <option value="facebook">Facebook</option>
            <option value="search">Google / Tìm kiếm</option>
            <option value="music">TikTok</option>
            <option value="phone">Điện thoại</option>
            <option value="footprints">Khách vãng lai</option>
            <option value="users">Giới thiệu</option>
            <option value="globe">Website / Khác</option>
          </select>
        </div>
        <div class="fg col-4">
          <label>Màu</label>
          <input type="color" name="color" id="sourceColor" value="#06b6d4" style="height:44px;padding:4px;cursor:pointer;">
        </div>
        <div class="fg col-12">
          <label>Mô tả</label>
          <textarea name="description" id="sourceDescription" placeholder="Mô tả ngắn về nguồn khách..." style="min-height:70px;"></textarea>
        </div>
        <div class="fg col-12">
          <label style="flex-direction:row;gap:8px;align-items:center;cursor:pointer;">
            <input type="checkbox" name="is_active" id="sourceActive" checked style="width:18px;height:18px;accent-color:#06b6d4;">
            <span>Kích hoạt nguồn này</span>
          </label>
        </div>
      </div>
    </div>
    <div class="modal-foot">
      <button type="button" class="btn btn-ghost" onclick="closeModal('sourceModal')">Hủy</button>
      <button type="submit" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        Lưu nguồn
      </button>
    </div>
  </form>
</div>

<!-- ═══════════════════════════════════════════ -->
<!-- MODAL: DISEASE -->
<!-- ═══════════════════════════════════════════ -->
<div class="modal-ov" id="diseaseModal">
  <form method="post" action="?c=setting&a=diseaseStore" class="modal" style="max-width:700px;" id="diseaseForm">
    <input type="hidden" name="id" id="diseaseId">
    <div class="modal-head">
      <h3 id="diseaseModalTitle">Thêm bệnh lý mới</h3>
      <button type="button" class="modal-close" onclick="closeModal('diseaseModal')">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="fg col-12">
          <label>Tên bệnh lý <span class="req">*</span></label>
          <input type="text" name="name" id="diseaseName" placeholder="VD: Cận thị - Lasik" required>
        </div>
        <div class="fg col-6">
          <label>Danh mục</label>
          <select name="category" id="diseaseCategory">
            <option value="refraction">Khúc xạ (Cận, Viễn, Loạn)</option>
            <option value="cataract">Thủy tinh thể</option>
            <option value="glaucoma">Glaucoma</option>
            <option value="retina">Võng mạc</option>
            <option value="strabismus">Mắt lác</option>
            <option value="general">Khám tổng quát</option>
            <option value="other">Khác</option>
          </select>
        </div>
        <div class="fg col-6">
          <label>Icon</label>
          <select name="icon" id="diseaseIcon">
            <option value="glasses">Kính / Cận thị</option>
            <option value="sparkles">Smile / Lasik</option>
            <option value="aperture">Thủy tinh thể</option>
            <option value="droplet">Glaucoma</option>
            <option value="droplets">Khô mắt</option>
            <option value="eye">Võng mạc / Mắt</option>
            <option value="move">Mắt lác</option>
            <option value="clipboard">Khám tổng quát</option>
          </select>
        </div>
        <div class="fg col-6">
          <label>Giá từ (VNĐ)</label>
          <input type="number" name="price_from" id="diseasePriceFrom" placeholder="0" step="100000" value="0">
        </div>
        <div class="fg col-6">
          <label>Giá đến (VNĐ)</label>
          <input type="number" name="price_to" id="diseasePriceTo" placeholder="0" step="100000" value="0">
        </div>
        <div class="fg col-6">
          <label>Màu hiển thị</label>
          <input type="color" name="color" id="diseaseColor" value="#06b6d4" style="height:44px;padding:4px;cursor:pointer;">
        </div>
        <div class="fg col-6">
          <label>Thứ tự hiển thị</label>
          <input type="number" name="sort_order" id="diseaseSortOrder" value="0">
        </div>
        <div class="fg col-12">
          <label>Mô tả</label>
          <textarea name="description" id="diseaseDescription" placeholder="Mô tả về bệnh lý, phương pháp điều trị..." style="min-height:80px;"></textarea>
        </div>
        <div class="fg col-12">
          <label style="flex-direction:row;gap:8px;align-items:center;cursor:pointer;">
            <input type="checkbox" name="is_active" id="diseaseActive" checked style="width:18px;height:18px;accent-color:#06b6d4;">
            <span>Kích hoạt bệnh lý này</span>
          </label>
        </div>
      </div>
    </div>
    <div class="modal-foot">
      <button type="button" class="btn btn-ghost" onclick="closeModal('diseaseModal')">Hủy</button>
      <button type="submit" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        Lưu bệnh lý
      </button>
    </div>
  </form>
</div>

<script>
// ═══ TABS ═══
function switchTab(el, id) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
  const target = document.getElementById('tab-' + id);
  target.style.display = 'block';
  gsap.from(target.querySelector('.card'), { opacity: 0, y: 20, duration: .4, ease: 'power3.out' });
  history.replaceState(null, '', '#' + id);
}

// Auto-open tab from hash
if (location.hash) {
  const tabId = location.hash.substring(1);
  const tabEl = [...document.querySelectorAll('.tab')].find((t, i) => 
    ['sources','diseases','users','system'][i] === tabId
  );
  if (tabEl) switchTab(tabEl, tabId);
}

// ═══ MODAL HELPERS ═══
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
document.querySelectorAll('.modal-ov').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) m.classList.remove('show'); });
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') document.querySelectorAll('.modal-ov.show').forEach(m => m.classList.remove('show'));
});

// ═══ SOURCE MODAL ═══
function openSourceModal(data = null) {
  const form = document.getElementById('sourceForm');
  const title = document.getElementById('sourceModalTitle');
  
  if (data) {
    title.textContent = 'Chỉnh sửa nguồn khách';
    form.action = '?c=setting&a=sourceUpdate';
    document.getElementById('sourceId').value = data.id;
    document.getElementById('sourceName').value = data.name;
    document.getElementById('sourceType').value = data.type;
    document.getElementById('sourceIcon').value = data.icon || 'globe';
    document.getElementById('sourceColor').value = data.color || '#06b6d4';
    document.getElementById('sourceDescription').value = data.description || '';
    document.getElementById('sourceActive').checked = data.is_active == 1;
  } else {
    title.textContent = 'Thêm nguồn khách mới';
    form.action = '?c=setting&a=sourceStore';
    form.reset();
    document.getElementById('sourceId').value = '';
    document.getElementById('sourceColor').value = '#06b6d4';
    document.getElementById('sourceActive').checked = true;
  }
  
  document.getElementById('sourceModal').classList.add('show');
  setTimeout(() => document.getElementById('sourceName').focus(), 200);
}

// ═══ DISEASE MODAL ═══
function openDiseaseModal(data = null) {
  const form = document.getElementById('diseaseForm');
  const title = document.getElementById('diseaseModalTitle');
  
  if (data) {
    title.textContent = 'Chỉnh sửa bệnh lý';
    form.action = '?c=setting&a=diseaseUpdate';
    document.getElementById('diseaseId').value = data.id;
    document.getElementById('diseaseName').value = data.name;
    document.getElementById('diseaseCategory').value = data.category;
    document.getElementById('diseaseIcon').value = data.icon || 'eye';
    document.getElementById('diseaseColor').value = data.color || '#06b6d4';
    document.getElementById('diseasePriceFrom').value = data.price_from;
    document.getElementById('diseasePriceTo').value = data.price_to;
    document.getElementById('diseaseSortOrder').value = data.sort_order;
    document.getElementById('diseaseDescription').value = data.description || '';
    document.getElementById('diseaseActive').checked = data.is_active == 1;
  } else {
    title.textContent = 'Thêm bệnh lý mới';
    form.action = '?c=setting&a=diseaseStore';
    form.reset();
    document.getElementById('diseaseId').value = '';
    document.getElementById('diseaseColor').value = '#06b6d4';
    document.getElementById('diseaseActive').checked = true;
    document.getElementById('diseasePriceFrom').value = 0;
    document.getElementById('diseasePriceTo').value = 0;
    document.getElementById('diseaseSortOrder').value = 0;
  }
  
  document.getElementById('diseaseModal').classList.add('show');
  setTimeout(() => document.getElementById('diseaseName').focus(), 200);
}

// ═══ TOGGLE ACTIVE ═══
async function toggleSource(id, btn) {
  const res = await fetch('?c=setting&a=sourceToggle', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'id=' + id
  });
  const data = await res.json();
  if (data.ok) {
    btn.className = 'badge ' + (data.active ? 'badge-surgery' : 'badge-lost');
    btn.style.cursor = 'pointer';
    btn.style.border = '1px solid';
    btn.innerHTML = `<span class="bdot"></span>${data.active ? 'Hoạt động' : 'Tắt'}`;
    gsap.from(btn, { scale: .9, duration: .3, ease: 'back.out(3)' });
  }
}

async function toggleDisease(id, btn) {
  const res = await fetch('?c=setting&a=diseaseToggle', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'id=' + id
  });
  const data = await res.json();
  if (data.ok) {
    btn.className = 'badge ' + (data.active ? 'badge-surgery' : 'badge-lost');
    btn.style.cursor = 'pointer';
    btn.style.border = '1px solid';
    btn.innerHTML = `<span class="bdot"></span>${data.active ? 'Hoạt động' : 'Tắt'}`;
    gsap.from(btn, { scale: .9, duration: .3, ease: 'back.out(3)' });
  }
}

// ═══ DELETE ═══
function deleteSource(id, name) {
  Swal.fire({
    title: 'Xóa nguồn khách?',
    html: `Bạn có chắc muốn xóa nguồn <b>${name}</b>?<br><small style="color:#94a3b8;">Hành động này không thể hoàn tác.</small>`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Xóa',
    cancelButtonText: 'Hủy',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#64748b',
    background: '#0d1526',
    color: '#e2e8f0'
  }).then(r => {
    if (r.isConfirmed) location.href = '?c=setting&a=sourceDelete&id=' + id;
  });
}

function deleteDisease(id, name) {
  Swal.fire({
    title: 'Xóa bệnh lý?',
    html: `Bạn có chắc muốn xóa <b>${name}</b>?<br><small style="color:#94a3b8;">Hành động này không thể hoàn tác.</small>`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Xóa',
    cancelButtonText: 'Hủy',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#64748b',
    background: '#0d1526',
    color: '#e2e8f0'
  }).then(r => {
    if (r.isConfirmed) location.href = '?c=setting&a=diseaseDelete&id=' + id;
  });
}

// ═══ CONFETTI khi lưu thành công ═══
<?php if (Helper::getFlash()): ?>
// Flash sẽ tự hiển thị qua footer.php
<?php endif; ?>
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>