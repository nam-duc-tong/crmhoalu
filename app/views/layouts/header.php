<?php
$user = Auth::user();
$flash = Helper::getFlash();
$initials = $user ? mb_substr($user['full_name'], 0, 1) : '?';
$active = $activePage ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= Helper::e($pageTitle ?? 'CRM') ?> — <?= APP_NAME ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
  <style>
    /* ═══════════════════════════════════════════ */
    /* ════════ CRM HOA LƯ — V5.0 ULTRA ═════════ */
    /* ═══════════════════════════════════════════ */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    html {
      scroll-behavior: smooth
    }

    body {
      font-family: 'Inter', -apple-system, sans-serif;
      background: #131b2e;
      color: #e2e8f0;
      font-size: 14px;
      line-height: 1.5;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
      min-height: 100vh;
    }

    svg {
      display: block
    }

    a {
      color: inherit;
      text-decoration: none
    }

    button {
      font-family: inherit;
      cursor: pointer;
      border: 0;
      background: 0
    }

    input,
    select,
    textarea {
      font-family: inherit;
      font-size: inherit;
      outline: none
    }

    img {
      max-width: 100%;
      display: block
    }

    /* ═══ LAYOUT ═══ */
    .app {
      display: flex;
      min-height: 100vh;
      position: relative
    }

    .main {
      flex: 1;
      margin-left: 260px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: relative;
      z-index: 1
    }

    .body {
      padding: 24px;
      flex: 1
    }

    .aurora {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background:
        radial-gradient(ellipse 60% 40% at 15% 5%, rgba(6, 182, 212, .12), transparent 60%),
        radial-gradient(ellipse 50% 40% at 90% 15%, rgba(139, 92, 246, .14), transparent 60%),
        radial-gradient(ellipse 50% 40% at 50% 100%, rgba(236, 72, 153, .08), transparent 60%),
        #131b2e;
    }

    .aurora::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(148, 163, 184, .04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(148, 163, 184, .04) 1px, transparent 1px);
      background-size: 50px 50px;
      mask-image: radial-gradient(ellipse 80% 60% at center, #000 30%, transparent 80%);
      -webkit-mask-image: radial-gradient(ellipse 80% 60% at center, #000 30%, transparent 80%);
    }

    /* ═══ SIDEBAR ═══ */
    .sb {
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
      width: 260px;
      background: linear-gradient(180deg, #1a2332 0%, #131b2e 100%);
      border-right: 1px solid rgba(148, 163, 184, .12);
      z-index: 100;
      display: flex;
      flex-direction: column;
      transition: transform .3s cubic-bezier(.16, 1, .3, 1);
    }

    .sb::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(6, 182, 212, .4), transparent);
    }

    .sb-brand {
      padding: 22px 20px;
      border-bottom: 1px solid rgba(148, 163, 184, .1);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .sb-logo {
      width: 44px;
      height: 44px;
      border-radius: 13px;
      flex-shrink: 0;
      background: linear-gradient(135deg, #06b6d4, #3b82f6, #8b5cf6);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 22px rgba(6, 182, 212, .35);
      position: relative;
      overflow: hidden;
    }

    .sb-logo::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, transparent 40%, rgba(255, 255, 255, .3) 50%, transparent 60%);
      transform: translateX(-100%);
      animation: shine 3s infinite;
    }

    @keyframes shine {
      to {
        transform: translateX(100%)
      }
    }

    .sb-logo svg {
      width: 22px;
      height: 22px;
      stroke: #fff;
      fill: none;
      stroke-width: 2
    }

    .sb-brand-txt strong {
      display: block;
      font-family: 'Space Grotesk';
      font-size: 16px;
      color: #fff;
      letter-spacing: .5px;
      font-weight: 700;
    }

    .sb-brand-txt small {
      display: block;
      font-size: 9px;
      color: #22d3ee;
      letter-spacing: .15em;
      font-weight: 700;
      margin-top: 3px;
    }

    .sb-nav {
      flex: 1;
      padding: 14px 12px;
      overflow-y: auto
    }

    .sb-nav::-webkit-scrollbar {
      width: 4px
    }

    .sb-nav::-webkit-scrollbar-thumb {
      background: rgba(148, 163, 184, .15);
      border-radius: 2px
    }

    .sb-section {
      font-size: 10px;
      font-weight: 700;
      color: #475569;
      letter-spacing: .15em;
      text-transform: uppercase;
      padding: 14px 12px 8px;
    }

    .sb-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 14px;
      border-radius: 11px;
      color: #94a3b8;
      font-weight: 500;
      font-size: 13.5px;
      margin-bottom: 3px;
      position: relative;
      transition: all .2s cubic-bezier(.16, 1, .3, 1);
    }

    .sb-link svg {
      width: 18px;
      height: 18px;
      flex-shrink: 0;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round
    }

    .sb-link:hover {
      background: rgba(148, 163, 184, .06);
      color: #fff;
      transform: translateX(3px)
    }

    .sb-link.active {
      background: linear-gradient(90deg, rgba(6, 182, 212, .15), rgba(139, 92, 246, .08));
      color: #fff;
      box-shadow: inset 3px 0 0 #22d3ee;
    }

    .sb-link.active svg {
      color: #22d3ee
    }

    .sb-foot {
      padding: 14px;
      border-top: 1px solid rgba(148, 163, 184, .1);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .sb-user {
      flex: 1;
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 0;
      padding: 6px;
      border-radius: 10px;
      transition: background .2s
    }

    .sb-user:hover {
      background: rgba(148, 163, 184, .06)
    }

    .sb-ava {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      flex-shrink: 0;
      background: linear-gradient(135deg, #06b6d4, #8b5cf6);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-weight: 700;
      font-size: 14px;
      box-shadow: 0 4px 12px rgba(6, 182, 212, .3);
      position: relative;
    }

    .sb-ava::after {
      content: '';
      position: absolute;
      bottom: -2px;
      right: -2px;
      width: 11px;
      height: 11px;
      border-radius: 50%;
      background: #34d399;
      border: 2px solid #131b2e;
    }

    .sb-user-info {
      min-width: 0;
      overflow: hidden
    }

    .sb-user-info strong {
      display: block;
      font-size: 12.5px;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .sb-user-info small {
      display: block;
      font-size: 10px;
      color: #22d3ee;
      font-weight: 600;
      letter-spacing: .08em;
      text-transform: uppercase;
    }

    .sb-logout {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      flex-shrink: 0;
      background: rgba(239, 68, 68, .1);
      color: #f87171;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all .2s;
      cursor: pointer;
    }

    .sb-logout:hover {
      background: #ef4444;
      color: #fff;
      transform: scale(1.05)
    }

    .sb-logout svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2
    }

    /* ═══ TOPBAR ═══ */
    .top {
      position: sticky;
      top: 0;
      z-index: 50;
      background: rgba(19, 27, 46, .9);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(148, 163, 184, .1);
      padding: 14px 24px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .top-menu {
      width: 40px;
      height: 40px;
      border-radius: 11px;
      background: rgba(15, 23, 42, .6);
      color: #94a3b8;
      border: 1px solid rgba(148, 163, 184, .1);
      display: none;
      align-items: center;
      justify-content: center;
      transition: all .2s;
      cursor: pointer;
    }

    .top-menu svg {
      width: 18px;
      height: 18px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2
    }

    .top-title {
      font-family: 'Space Grotesk';
      font-size: 20px;
      font-weight: 700;
      color: #fff;
      flex: 1;
      min-width: 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .top-search {
      position: relative;
      display: block
    }

    .top-search svg {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      width: 15px;
      height: 15px;
      color: #64748b;
      pointer-events: none;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
    }

    .top-search input {
      width: 280px;
      height: 40px;
      padding: 0 14px 0 40px;
      background: rgba(30, 41, 59, .6);
      border: 1px solid rgba(148, 163, 184, .15);
      border-radius: 11px;
      color: #f1f5f9;
      font-size: 13px;
      transition: all .2s;
      cursor: pointer;
    }

    .top-search input::placeholder {
      color: #64748b
    }

    .top-search input:focus {
      border-color: rgba(34, 211, 238, .5);
      box-shadow: 0 0 0 3px rgba(6, 182, 212, .1);
      background: rgba(15, 23, 42, .9);
    }

    .top-btn {
      width: 40px;
      height: 40px;
      border-radius: 11px;
      background: rgba(30, 41, 59, .6);
      border: 1px solid rgba(148, 163, 184, .15);
      color: #94a3b8;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all .2s;
      position: relative;
    }

    .top-btn svg {
      width: 18px;
      height: 18px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .top-btn:hover {
      color: #22d3ee;
      border-color: rgba(34, 211, 238, .4);
      transform: translateY(-1px)
    }

    .top-btn .dot {
      position: absolute;
      top: 9px;
      right: 9px;
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #ef4444;
      border: 2px solid #131b2e;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1
      }

      50% {
        opacity: .4
      }
    }

    /* ═══ BUTTONS ═══ */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      height: 40px;
      padding: 0 18px;
      border-radius: 11px;
      font-size: 13px;
      font-weight: 600;
      white-space: nowrap;
      transition: all .25s cubic-bezier(.16, 1, .3, 1);
      cursor: pointer;
      border: 0;
    }

    .btn svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
      flex-shrink: 0;
    }

    .btn-primary {
      background: linear-gradient(135deg, #06b6d4, #3b82f6);
      color: #fff;
      box-shadow: 0 6px 16px rgba(6, 182, 212, .3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 24px rgba(6, 182, 212, .4)
    }

    .btn-ghost {
      background: rgba(30, 41, 59, .6);
      color: #cbd5e1;
      border: 1px solid rgba(148, 163, 184, .18);
    }

    .btn-ghost:hover {
      background: rgba(148, 163, 184, .1);
      color: #fff
    }

    .btn-sm {
      height: 34px;
      padding: 0 14px;
      font-size: 12px;
      border-radius: 9px
    }

    .btn-icon {
      width: 34px;
      height: 34px;
      padding: 0;
      border-radius: 9px
    }

    /* ═══ CARDS ═══ */
    .card {
      background: rgba(30, 41, 59, .5);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(148, 163, 184, .12);
      border-radius: 18px;
      overflow: hidden;
      transition: border-color .3s;
    }

    .card:hover {
      border-color: rgba(148, 163, 184, .15)
    }

    .card-head {
      padding: 18px 22px;
      border-bottom: 1px solid rgba(148, 163, 184, .06);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
    }

    .card-head h3 {
      font-family: 'Space Grotesk';
      font-size: 15px;
      font-weight: 700;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 9px;
    }

    .card-head h3 svg {
      width: 18px;
      height: 18px;
      color: #22d3ee;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .card-body {
      padding: 22px
    }

    .card-foot {
      padding: 16px 22px;
      border-top: 1px solid rgba(148, 163, 184, .06);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
    }

    /* ═══ STATS ═══ */
    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }

    .stat {
      background: rgba(30, 41, 59, .5);
      backdrop-filter: blur(16px);
      border: 1px solid rgba(148, 163, 184, .12);
      border-radius: 18px;
      padding: 22px;
      position: relative;
      overflow: hidden;
      transition: all .35s cubic-bezier(.16, 1, .3, 1);
    }

    .stat::before {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 140px;
      height: 140px;
      border-radius: 50%;
      filter: blur(35px);
      opacity: .2;
      transition: transform .5s;
    }

    .stat:hover {
      transform: translateY(-4px);
      border-color: rgba(34, 211, 238, .25);
      box-shadow: 0 20px 40px rgba(0, 0, 0, .3);
    }

    .stat:hover::before {
      transform: scale(1.4)
    }

    .stat-icon {
      width: 52px;
      height: 52px;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 16px;
    }

    .stat-icon svg {
      width: 24px;
      height: 24px;
      stroke: #fff;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .stat-val {
      font-family: 'Space Grotesk';
      font-size: 28px;
      font-weight: 700;
      color: #fff;
      line-height: 1.1;
      margin-bottom: 5px;
    }

    .stat-lbl {
      font-size: 11px;
      font-weight: 700;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 10px;
    }

    .stat-hint {
      font-size: 11.5px;
      color: #94a3b8;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .stat-hint strong {
      color: #34d399;
      font-weight: 600;
    }

    .stat.c-cyan::before {
      background: #06b6d4
    }

    .stat.c-cyan .stat-icon {
      background: linear-gradient(135deg, #06b6d4, #0891b2);
      box-shadow: 0 8px 22px rgba(6, 182, 212, .35);
    }

    .stat.c-amber::before {
      background: #f59e0b
    }

    .stat.c-amber .stat-icon {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      box-shadow: 0 8px 22px rgba(245, 158, 11, .35);
    }

    .stat.c-emerald::before {
      background: #10b981
    }

    .stat.c-emerald .stat-icon {
      background: linear-gradient(135deg, #10b981, #059669);
      box-shadow: 0 8px 22px rgba(16, 185, 129, .35);
    }

    .stat.c-purple::before {
      background: #8b5cf6
    }

    .stat.c-purple .stat-icon {
      background: linear-gradient(135deg, #8b5cf6, #7c3aed);
      box-shadow: 0 8px 22px rgba(139, 92, 246, .35);
    }

    /* ═══ TABLE ═══ */
    .tbl-wrap {
      overflow-x: auto
    }

    .tbl {
      width: 100%;
      border-collapse: collapse;
      font-size: 13.5px
    }

    .tbl thead th {
      text-align: left;
      padding: 14px 18px;
      font-size: 10.5px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: .08em;
      background: rgba(30, 41, 59, .5);
      border-bottom: 1px solid rgba(148, 163, 184, .12);
      white-space: nowrap;
    }

    .tbl tbody td {
      padding: 15px 18px;
      border-bottom: 1px solid rgba(148, 163, 184, .05);
      vertical-align: middle;
    }

    .tbl tbody tr {
      transition: background .15s
    }

    .tbl tbody tr:hover {
      background: rgba(148, 163, 184, .06)
    }

    .tbl tbody tr:last-child td {
      border-bottom: 0
    }

    /* ═══ AVATAR ═══ */
    .ava {
      width: 38px;
      height: 38px;
      border-radius: 11px;
      flex-shrink: 0;
      background: linear-gradient(135deg, #06b6d4, #8b5cf6);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-weight: 700;
      font-size: 14px;
      box-shadow: 0 4px 10px rgba(6, 182, 212, .25);
    }

    .ava-sm {
      width: 32px;
      height: 32px;
      border-radius: 9px;
      font-size: 12px
    }

    .ava-lg {
      width: 96px;
      height: 96px;
      border-radius: 24px;
      font-size: 40px;
      box-shadow: 0 12px 32px rgba(0, 0, 0, .4)
    }

    /* ═══ CHIPS ═══ */
    .chip {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 7px;
      background: rgba(6, 182, 212, .1);
      color: #22d3ee;
      font-size: 11px;
      font-weight: 600;
      font-family: ui-monospace, monospace;
      border: 1px solid rgba(6, 182, 212, .18);
    }

    .tag {
      display: inline-block;
      padding: 3px 11px;
      border-radius: 20px;
      background: rgba(139, 92, 246, .12);
      color: #a78bfa;
      font-size: 11px;
      font-weight: 600;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 11px;
      border-radius: 8px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .02em;
      border: 1px solid;
      white-space: nowrap;
    }

    .badge .bdot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: currentColor;
      animation: pulse 2s infinite
    }

    .badge-new {
      background: rgba(6, 182, 212, .12);
      color: #22d3ee;
      border-color: rgba(6, 182, 212, .28)
    }

    .badge-contacted {
      background: rgba(59, 130, 246, .12);
      color: #60a5fa;
      border-color: rgba(59, 130, 246, .28)
    }

    .badge-appointment {
      background: rgba(245, 158, 11, .12);
      color: #fbbf24;
      border-color: rgba(245, 158, 11, .28)
    }

    .badge-consulted {
      background: rgba(139, 92, 246, .12);
      color: #a78bfa;
      border-color: rgba(139, 92, 246, .28)
    }

    .badge-surgery {
      background: rgba(16, 185, 129, .12);
      color: #34d399;
      border-color: rgba(16, 185, 129, .28)
    }

    .badge-done {
      background: rgba(16, 185, 129, .12);
      color: #34d399;
      border-color: rgba(16, 185, 129, .28)
    }

    .badge-lost {
      background: rgba(239, 68, 68, .12);
      color: #f87171;
      border-color: rgba(239, 68, 68, .28)
    }

    /* ═══ FORM ═══ */
    .form-card {
      max-width: 1000px;
      margin: 0 auto
    }

    .form-section {
      margin-bottom: 28px;
      padding-bottom: 24px;
      border-bottom: 1px solid rgba(148, 163, 184, .06);
    }

    .form-section:last-child {
      border-bottom: 0;
      padding-bottom: 0;
      margin-bottom: 0
    }

    .form-section-title {
      font-family: 'Space Grotesk';
      font-size: 13px;
      font-weight: 700;
      color: #22d3ee;
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-section-title svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 16px
    }

    .col-3 {
      grid-column: span 3
    }

    .col-4 {
      grid-column: span 4
    }

    .col-6 {
      grid-column: span 6
    }

    .col-8 {
      grid-column: span 8
    }

    .col-12 {
      grid-column: span 12
    }

    .fg {
      display: flex;
      flex-direction: column;
      gap: 7px
    }

    .fg label {
      font-size: 12px;
      font-weight: 600;
      color: #cbd5e1;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .fg label .req {
      color: #ef4444;
      font-size: 14px
    }

    .fg input,
    .fg select,
    .fg textarea {
      width: 100%;
      height: 44px;
      padding: 0 14px;
      background: rgba(15, 23, 42, .7);
      border: 1.5px solid rgba(148, 163, 184, .18);
      border-radius: 11px;
      color: #f1f5f9;
      font-size: 13.5px;
      transition: all .2s;
    }

    .fg textarea {
      height: auto;
      min-height: 100px;
      padding: 12px 14px;
      resize: vertical;
      line-height: 1.5
    }

    .fg input::placeholder,
    .fg textarea::placeholder {
      color: #475569
    }

    .fg input:focus,
    .fg select:focus,
    .fg textarea:focus {
      border-color: rgba(34, 211, 238, .6);
      background: rgba(15, 23, 42, .95);
      box-shadow: 0 0 0 3px rgba(6, 182, 212, .15);
    }

    .fg select {
      appearance: none;
      -webkit-appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      padding-right: 40px;
    }

    .fg select option {
      background: #0f172a;
      color: #e2e8f0
    }

    .form-actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      padding-top: 20px;
      margin-top: 8px;
      border-top: 1px solid rgba(148, 163, 184, .06);
    }

    /* ═══ PIPELINE ═══ */
    .pipeline {
      display: grid;
      grid-template-columns: repeat(5, minmax(260px, 1fr));
      gap: 16px;
      overflow-x: auto;
      padding-bottom: 12px;
    }

    .pipe-col {
      display: flex;
      flex-direction: column
    }

    .pipe-head {
      padding: 14px 16px;
      border-radius: 14px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #fff;
      font-weight: 700;
      font-size: 13px;
      position: relative;
      overflow: hidden;
    }

    .pipe-head::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, currentColor, transparent);
      opacity: .18;
    }

    .pipe-head>* {
      position: relative;
      z-index: 1
    }

    .pipe-head .pcount {
      background: rgba(255, 255, 255, .22);
      padding: 3px 11px;
      border-radius: 20px;
      font-size: 11px;
    }

    .pipe-head.h-new {
      background: linear-gradient(135deg, #06b6d4, #0891b2)
    }

    .pipe-head.h-contacted {
      background: linear-gradient(135deg, #3b82f6, #2563eb)
    }

    .pipe-head.h-appointment {
      background: linear-gradient(135deg, #f59e0b, #d97706)
    }

    .pipe-head.h-consulted {
      background: linear-gradient(135deg, #8b5cf6, #7c3aed)
    }

    .pipe-head.h-surgery {
      background: linear-gradient(135deg, #10b981, #059669)
    }

    .pipe-body {
      flex: 1;
      min-height: 200px;
      padding: 4px;
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      transition: background .2s;
    }

    .pipe-body.drag-over {
      background: rgba(6, 182, 212, .06);
      box-shadow: inset 0 0 0 2px rgba(6, 182, 212, .3)
    }

    .pipe-card {
      background: rgba(30, 41, 59, .85);
      border: 1px solid rgba(148, 163, 184, .15);
      border-radius: 14px;
      padding: 14px;
      cursor: grab;
      transition: all .25s cubic-bezier(.16, 1, .3, 1);
    }

    .pipe-card:hover {
      transform: translateY(-2px);
      border-color: rgba(34, 211, 238, .35);
      box-shadow: 0 12px 24px rgba(6, 182, 212, .12);
    }

    .pipe-card:active {
      cursor: grabbing
    }

    .pipe-ghost {
      opacity: .3;
      background: rgba(6, 182, 212, .15) !important;
      border: 2px dashed rgba(6, 182, 212, .5) !important;
    }

    /* ═══ TIMELINE ═══ */
    .timeline {
      position: relative;
      padding-left: 8px
    }

    .timeline::before {
      content: '';
      position: absolute;
      left: 27px;
      top: 8px;
      bottom: 8px;
      width: 2px;
      background: linear-gradient(to bottom, rgba(6, 182, 212, .3), rgba(139, 92, 246, .15), transparent);
    }

    .tl-item {
      display: flex;
      gap: 14px;
      padding: 10px 0;
      position: relative;
    }

    .tl-icon {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 1;
      box-shadow: 0 6px 14px rgba(0, 0, 0, .25);
    }

    .tl-icon svg {
      width: 18px;
      height: 18px;
      stroke: #fff;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .tl-call {
      background: linear-gradient(135deg, #3b82f6, #2563eb)
    }

    .tl-sms {
      background: linear-gradient(135deg, #8b5cf6, #7c3aed)
    }

    .tl-email {
      background: linear-gradient(135deg, #06b6d4, #0891b2)
    }

    .tl-meeting {
      background: linear-gradient(135deg, #f59e0b, #d97706)
    }

    .tl-note {
      background: linear-gradient(135deg, #64748b, #475569)
    }

    .tl-status_change {
      background: linear-gradient(135deg, #6366f1, #4f46e5)
    }

    .tl-surgery {
      background: linear-gradient(135deg, #10b981, #059669)
    }

    .tl-body {
      flex: 1;
      padding-top: 4px;
      min-width: 0
    }

    .tl-body strong {
      color: #fff;
      font-size: 13px
    }

    .tl-body p {
      color: #cbd5e1;
      font-size: 13px;
      margin-top: 2px
    }

    .tl-body small {
      color: #64748b;
      font-size: 11px
    }

    /* ═══ MODAL ═══ */
    .modal-ov {
      position: fixed;
      inset: 0;
      z-index: 1000;
      background: rgba(0, 0, 0, .75);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .modal-ov.show {
      display: flex;
      animation: fadeIn .25s
    }

    .modal {
      background: #1a2332;
      border: 1px solid rgba(148, 163, 184, .2);
      border-radius: 20px;
      width: 100%;
      max-width: 820px;
      max-height: 90vh;
      overflow-y: auto;
      animation: modalIn .4s cubic-bezier(.16, 1, .3, 1);
      box-shadow: 0 40px 80px rgba(0, 0, 0, .5);
    }

    .modal-head {
      padding: 20px 24px;
      border-bottom: 1px solid rgba(148, 163, 184, .15);
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      background: #1a2332;
      z-index: 2;
    }

    .modal-foot {
      padding: 16px 24px;
      border-top: 1px solid rgba(148, 163, 184, .15);
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      position: sticky;
      bottom: 0;
      background: #1a2332;
    }

    .modal-head h3 {
      font-family: 'Space Grotesk';
      font-size: 17px;
      font-weight: 700;
      color: #fff
    }

    .modal-close {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      background: rgba(148, 163, 184, .08);
      color: #94a3b8;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all .2s;
      cursor: pointer;
    }

    .modal-close:hover {
      background: rgba(239, 68, 68, .15);
      color: #f87171
    }

    .modal-close svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2.5
    }

    .modal-body {
      padding: 22px 24px
    }



    /* ═══ UTILS ═══ */
    .empty {
      text-align: center;
      padding: 60px 20px;
      color: #64748b
    }

    .empty svg {
      width: 52px;
      height: 52px;
      margin: 0 auto 14px;
      opacity: .35;
      stroke: currentColor;
      fill: none;
      stroke-width: 1.5
    }

    .empty p {
      font-size: 13.5px
    }

    .flex {
      display: flex
    }

    .flex-col {
      flex-direction: column
    }

    .items-center {
      align-items: center
    }

    .items-start {
      align-items: flex-start
    }

    .justify-between {
      justify-content: space-between
    }

    .justify-center {
      justify-content: center
    }

    .gap-2 {
      gap: 8px
    }

    .gap-3 {
      gap: 12px
    }

    .gap-4 {
      gap: 16px
    }

    .mb-3 {
      margin-bottom: 12px
    }

    .mb-4 {
      margin-bottom: 16px
    }

    .mb-5 {
      margin-bottom: 20px
    }

    .mb-6 {
      margin-bottom: 24px
    }

    .mt-4 {
      margin-top: 16px
    }

    .mt-6 {
      margin-top: 24px
    }

    .text-xs {
      font-size: 11.5px
    }

    .text-sm {
      font-size: 13px
    }

    .text-muted {
      color: #64748b
    }

    .text-white {
      color: #fff
    }

    .text-cyan {
      color: #22d3ee
    }

    .text-emerald {
      color: #34d399
    }

    .text-amber {
      color: #fbbf24
    }

    .text-red {
      color: #f87171
    }

    .fw-bold {
      font-weight: 700
    }

    .fw-semibold {
      font-weight: 600
    }

    .truncate {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap
    }

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px
    }

    .grid-3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px
    }

    /* ═══ ANIMATIONS ═══ */
    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(12px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0
      }

      to {
        opacity: 1
      }
    }

    @keyframes modalIn {
      from {
        opacity: 0;
        transform: translateY(30px) scale(.96)
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1)
      }
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-15px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(20px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    .alert {
      padding: 13px 16px;
      border-radius: 12px;
      font-size: 13.5px;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 18px;
      animation: fadeUp .3s;
    }

    .alert svg {
      width: 18px;
      height: 18px;
      flex-shrink: 0;
      stroke: currentColor;
      fill: none;
      stroke-width: 2
    }

    .alert-success {
      background: rgba(16, 185, 129, .12);
      border: 1px solid rgba(16, 185, 129, .28);
      color: #6ee7b7
    }

    .alert-danger {
      background: rgba(239, 68, 68, .12);
      border: 1px solid rgba(239, 68, 68, .28);
      color: #fca5a5
    }

    .alert-warning {
      background: rgba(245, 158, 11, .12);
      border: 1px solid rgba(245, 158, 11, .28);
      color: #fcd34d
    }

    .alert-info {
      background: rgba(6, 182, 212, .12);
      border: 1px solid rgba(6, 182, 212, .28);
      color: #67e8f9
    }

    .progress {
      width: 100%;
      height: 6px;
      background: rgba(148, 163, 184, .1);
      border-radius: 3px;
      overflow: hidden
    }

    .progress-fill {
      height: 100%;
      border-radius: 3px;
      background: linear-gradient(90deg, #06b6d4, #8b5cf6);
      transition: width 1s cubic-bezier(.16, 1, .3, 1);
    }

    /* ═══ V5.0 EXTRAS ═══ */
    .gsap-fade {
      opacity: 0;
      transform: translateY(20px)
    }

    .gsap-slide-left {
      opacity: 0;
      transform: translateX(-30px)
    }

    .cursor-glow {
      position: fixed;
      width: 500px;
      height: 500px;
      border-radius: 50%;
      pointer-events: none;
      z-index: 1;
      background: radial-gradient(circle, rgba(6, 182, 212, .08), transparent 60%);
      transform: translate(-50%, -50%);
      transition: opacity .3s;
      mix-blend-mode: screen;
    }

    .cmdk-overlay {
      position: fixed;
      inset: 0;
      z-index: 5000;
      background: rgba(0, 0, 0, .6);
      backdrop-filter: blur(12px);
      display: none;
      align-items: flex-start;
      justify-content: center;
      padding-top: 12vh;
    }

    .cmdk-overlay.show {
      display: flex;
      animation: fadeIn .2s
    }

    .cmdk {
      width: 100%;
      max-width: 640px;
      background: rgba(26, 35, 50, .98);
      border: 1px solid rgba(148, 163, 184, .25);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 40px 100px rgba(0, 0, 0, .6);
      animation: modalIn .3s cubic-bezier(.16, 1, .3, 1);
    }

    .cmdk-input {
      width: 100%;
      padding: 20px 24px;
      background: transparent;
      border: 0;
      color: #fff;
      font-size: 16px;
      outline: none;
      border-bottom: 1px solid rgba(148, 163, 184, .1);
    }

    .cmdk-input::placeholder {
      color: #475569
    }

    .cmdk-list {
      max-height: 400px;
      overflow-y: auto;
      padding: 8px
    }

    .cmdk-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      border-radius: 10px;
      cursor: pointer;
      transition: background .15s;
      color: #cbd5e1;
      font-size: 13.5px;
    }

    .cmdk-item:hover,
    .cmdk-item.active {
      background: linear-gradient(90deg, rgba(6, 182, 212, .15), rgba(139, 92, 246, .08));
      color: #fff;
    }

    .cmdk-item svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2
    }

    .cmdk-item .kbd {
      margin-left: auto;
      font-size: 10px;
      padding: 2px 6px;
      background: rgba(148, 163, 184, .1);
      border-radius: 4px;
      color: #64748b;
      font-family: ui-monospace, monospace;
    }

    .cmdk-section {
      font-size: 10px;
      font-weight: 700;
      color: #475569;
      text-transform: uppercase;
      letter-spacing: .1em;
      padding: 12px 16px 6px;
    }

    .noti-panel {
      position: fixed;
      top: 70px;
      right: 24px;
      width: 380px;
      max-height: 500px;
      background: rgba(26, 35, 50, .98);
      border: 1px solid rgba(148, 163, 184, .2);
      border-radius: 18px;
      z-index: 200;
      display: none;
      box-shadow: 0 20px 60px rgba(0, 0, 0, .5);
      animation: slideDown .3s cubic-bezier(.16, 1, .3, 1);
      overflow: hidden;
    }

    .noti-panel.show {
      display: block
    }

    .noti-head {
      padding: 16px 20px;
      border-bottom: 1px solid rgba(148, 163, 184, .1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .noti-head h3 {
      font-family: 'Space Grotesk';
      font-size: 15px;
      color: #fff;
      font-weight: 700
    }

    .noti-list {
      max-height: 400px;
      overflow-y: auto
    }

    .noti-item {
      padding: 14px 20px;
      display: flex;
      gap: 12px;
      border-bottom: 1px solid rgba(148, 163, 184, .05);
      cursor: pointer;
      transition: background .15s;
    }

    .noti-item:hover {
      background: rgba(148, 163, 184, .04)
    }

    .noti-item.unread {
      background: rgba(6, 182, 212, .04)
    }

    .noti-icon {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #06b6d4, #8b5cf6);
    }

    .noti-icon svg {
      width: 16px;
      height: 16px;
      stroke: #fff;
      fill: none;
      stroke-width: 2
    }

    .noti-body {
      flex: 1;
      min-width: 0
    }

    .noti-body strong {
      font-size: 13px;
      color: #fff;
      display: block;
      margin-bottom: 2px
    }

    .noti-body small {
      font-size: 11.5px;
      color: #94a3b8;
      line-height: 1.4;
      display: block
    }

    .noti-body .time {
      font-size: 10.5px;
      color: #64748b;
      margin-top: 4px
    }

    .fab {
      position: fixed;
      bottom: 32px;
      right: 32px;
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: linear-gradient(135deg, #06b6d4, #3b82f6);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 12px 32px rgba(6, 182, 212, .5);
      cursor: pointer;
      z-index: 90;
      transition: all .3s cubic-bezier(.16, 1, .3, 1);
    }

    .fab:hover {
      transform: scale(1.1) rotate(90deg);
      box-shadow: 0 16px 40px rgba(6, 182, 212, .7)
    }

    .fab svg {
      width: 24px;
      height: 24px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2.5
    }

    .fab-menu {
      position: fixed;
      bottom: 100px;
      right: 32px;
      display: none;
      flex-direction: column;
      gap: 10px;
      z-index: 90;
    }

    .fab-menu.show {
      display: flex
    }

    .fab-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 18px;
      border-radius: 14px;
      background: rgba(26, 35, 50, .98);
      border: 1px solid rgba(148, 163, 184, .2);
      color: #fff;
      font-size: 13px;
      font-weight: 500;
      box-shadow: 0 8px 24px rgba(0, 0, 0, .4);
      cursor: pointer;
      white-space: nowrap;
      transition: all .2s;
    }

    .fab-item:hover {
      transform: translateX(-6px);
      border-color: rgba(34, 211, 238, .5);
      box-shadow: 0 12px 32px rgba(6, 182, 212, .3);
    }

    .fab-item svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2
    }

    .fab-item .fbi-icon {
      width: 32px;
      height: 32px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .heatmap-cell {
      aspect-ratio: 1;
      border-radius: 3px;
      transition: all .2s;
      cursor: pointer;
    }

    .heatmap-cell:hover {
      transform: scale(1.5);
      box-shadow: 0 0 0 2px #06b6d4;
      z-index: 10;
      position: relative;
    }

    [data-tip] {
      position: relative
    }

    [data-tip]::after {
      content: attr(data-tip);
      position: absolute;
      bottom: calc(100% + 8px);
      left: 50%;
      transform: translateX(-50%) scale(.9);
      padding: 6px 10px;
      background: rgba(13, 21, 38, .98);
      color: #fff;
      font-size: 11.5px;
      white-space: nowrap;
      border-radius: 8px;
      border: 1px solid rgba(148, 163, 184, .15);
      opacity: 0;
      pointer-events: none;
      transition: all .2s;
      z-index: 1000;
    }

    [data-tip]:hover::after {
      opacity: 1;
      transform: translateX(-50%) scale(1)
    }

    .tabs {
      display: flex;
      gap: 4px;
      border-bottom: 1px solid rgba(148, 163, 184, .08);
      margin-bottom: 20px;
    }

    .tab {
      padding: 12px 20px;
      font-size: 13px;
      font-weight: 600;
      color: #94a3b8;
      cursor: pointer;
      position: relative;
      transition: color .2s;
      border-radius: 10px 10px 0 0;
    }

    .tab:hover {
      color: #e2e8f0
    }

    .tab.active {
      color: #22d3ee
    }

    .tab.active::after {
      content: '';
      position: absolute;
      bottom: -1px;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, #06b6d4, #8b5cf6);
      border-radius: 2px 2px 0 0;
      animation: tabSlide .3s cubic-bezier(.16, 1, .3, 1);
    }

    @keyframes tabSlide {
      from {
        transform: scaleX(0)
      }

      to {
        transform: scaleX(1)
      }
    }

    .quick-pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 14px;
      border-radius: 20px;
      background: rgba(15, 23, 42, .5);
      border: 1px solid rgba(148, 163, 184, .1);
      font-size: 12.5px;
      transition: all .2s;
    }

    .quick-pill:hover {
      border-color: rgba(34, 211, 238, .4);
      transform: translateY(-2px)
    }

    .quick-pill .dot-live {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #34d399;
      animation: pulse 2s infinite;
    }

    .apt-card {
      display: flex;
      gap: 14px;
      padding: 16px;
      background: rgba(30, 41, 59, .4);
      border: 1px solid rgba(148, 163, 184, .12);
      border-radius: 14px;
      transition: all .25s;
    }

    .apt-card:hover {
      border-color: rgba(34, 211, 238, .3);
      transform: translateX(4px)
    }

    .apt-date {
      width: 64px;
      flex-shrink: 0;
      text-align: center;
      padding: 8px 4px;
      background: linear-gradient(135deg, rgba(6, 182, 212, .15), rgba(139, 92, 246, .15));
      border-radius: 12px;
      border: 1px solid rgba(34, 211, 238, .2);
    }

    .apt-date .day {
      font-family: 'Space Grotesk';
      font-size: 24px;
      font-weight: 700;
      color: #22d3ee;
      line-height: 1;
    }

    .apt-date .month {
      font-size: 10px;
      color: #64748b;
      margin-top: 2px;
      text-transform: uppercase;
      letter-spacing: .08em;
      font-weight: 700;
    }

    .avatar-stack {
      display: flex
    }

    .avatar-stack .ava {
      margin-left: -10px;
      border: 2px solid #1a2332;
      transition: transform .2s;
    }

    .avatar-stack .ava:first-child {
      margin-left: 0
    }

    .avatar-stack .ava:hover {
      transform: translateY(-4px);
      z-index: 10;
      position: relative
    }

    /* ═══ RESPONSIVE ═══ */
    @media (max-width:1100px) {
      .sb {
        transform: translateX(-100%)
      }

      .sb.show {
        transform: translateX(0)
      }

      .main {
        margin-left: 0
      }

      .top-menu {
        display: flex
      }
    }

    @media (max-width:700px) {
      .top-search {
        display: none
      }

      .body {
        padding: 16px
      }

      .col-3,
      .col-4,
      .col-6,
      .col-8 {
        grid-column: span 12
      }

      .grid-2,
      .grid-3 {
        grid-template-columns: 1fr
      }

      .stat-val {
        font-size: 24px
      }

      .top-title {
        font-size: 16px
      }
    }

    /* ═══ PAGINATION ═══ */
    .card-foot a.page-btn,
    .card-foot .page-btn {
      text-decoration: none;
    }

    @media (max-width: 768px) {
      .card-foot {
        flex-direction: column;
        align-items: stretch !important;
      }

      .card-foot>div:last-child {
        justify-content: center;
      }

      .page-btn {
        min-width: 32px;
        height: 32px;
        font-size: 12px;
      }
    }
  </style>
</head>

<body>

  <div class="aurora"></div>
  <div class="cursor-glow" id="cursorGlow"></div>

  <div class="app">

    <!-- ═══ SIDEBAR (CHỈ 1 LẦN DUY NHẤT) ═══ -->
    <aside class="sb" id="sb">
      <div class="sb-brand">
        <div class="sb-logo">
          <svg viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
        </div>
        <div class="sb-brand-txt">
          <strong>HOA LƯ</strong>
          <small>EYE HOSPITAL CRM</small>
        </div>
      </div>

      <nav class="sb-nav">
        <div class="sb-section">Menu chính</div>

        <a href="?c=dashboard" class="sb-link <?= $active === 'dashboard' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="9" />
            <rect x="14" y="3" width="7" height="5" />
            <rect x="14" y="12" width="7" height="9" />
            <rect x="3" y="16" width="7" height="5" />
          </svg>
          <span>Dashboard</span>
        </a>

        <a href="?c=customer" class="sb-link <?= $active === 'customer' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>
          <span>Khách hàng</span>
        </a>

        <a href="?c=pipeline" class="sb-link <?= $active === 'pipeline' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <path d="M9 3v18M15 3v18" />
          </svg>
          <span>Pipeline</span>
        </a>

        <a href="?c=appointment" class="sb-link <?= $active === 'appointment' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
          </svg>
          <span>Lịch hẹn</span>
        </a>

        <a href="?c=khamdoan" class="sb-link <?= $active === 'khamdoan' ? 'active' : '' ?>">
          <svg viewBox="0 0 24 24">
            <path d="M3 11l19-9-9 19-2-8-8-2z" />
          </svg>
          <span>
            Nguồn khách hàng
            <span style="background:#06b6d4;color:#fff;font-size:9px;padding:2px 6px;border-radius:6px;margin-left:6px;">LIVE</span>
          </span>
        </a>

        <div class="sb-section">Bán hàng</div>

        <a href="?c=customer&a=create" class="sb-link">
          <svg viewBox="0 0 24 24">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="8.5" cy="7" r="4" />
            <line x1="20" y1="8" x2="20" y2="14" />
            <line x1="23" y1="11" x2="17" y2="11" />
          </svg>
          <span>Thêm khách hàng</span>
        </a>

        <?php if (Auth::isManager()): ?>
          <div class="sb-section">Quản trị</div>

          <a href="?c=report" class="sb-link <?= $active === 'report' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24">
              <line x1="12" y1="20" x2="12" y2="10" />
              <line x1="18" y1="20" x2="18" y2="4" />
              <line x1="6" y1="20" x2="6" y2="16" />
            </svg>
            <span>Báo cáo</span>
          </a>

          <a href="?c=setting" class="sb-link <?= $active === 'setting' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="3" />
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
            </svg>
            <span>Cài đặt</span>
          </a>
        <?php endif; ?>
      </nav>

      <div class="sb-foot">
        <div class="sb-user">
          <div class="sb-ava"><?= $initials ?></div>
          <div class="sb-user-info">
            <strong><?= Helper::e($user['full_name']) ?></strong>
            <small><?= $user['role'] ?></small>
          </div>
        </div>
        <a href="?c=auth&a=logout" class="sb-logout" data-tip="Đăng xuất">
          <svg viewBox="0 0 24 24">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
          </svg>
        </a>
      </div>
    </aside>

    <!-- ═══ MAIN (CHỈ 1 TOPBAR DUY NHẤT) ═══ -->
    <main class="main">
      <header class="top">
        <button class="top-menu" onclick="document.getElementById('sb').classList.toggle('show')">
          <svg viewBox="0 0 24 24">
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="18" x2="21" y2="18" />
          </svg>
        </button>

        <h1 class="top-title"><?= Helper::e($pageTitle ?? 'Dashboard') ?></h1>

        <div class="top-search" onclick="openCmdk()">
          <svg viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.35-4.35" />
          </svg>
          <input type="text" placeholder="Tìm kiếm... (Ctrl+K)" readonly>
        </div>

        <button class="top-btn" onclick="toggleNoti()" data-tip="Thông báo">
          <svg viewBox="0 0 24 24">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
          </svg>
          <span class="dot"></span>
        </button>
      </header>

      <div class="body">