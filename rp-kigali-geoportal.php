<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title>RP Kigali Smart Campus | 3D GeoPortal</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

  <script type="importmap">
    {
      "imports": {
        "three": "https://unpkg.com/three@0.128.0/build/three.module.js",
        "three/addons/": "https://unpkg.com/three@0.128.0/examples/jsm/"
      }
    }
  </script>

  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --bg:        #21262e;
      --bg2:       #1a1f26;
      --bg3:       #2a3040;
      --panel:     #1e2330;
      --border:    rgba(255,255,255,0.08);
      --border2:   rgba(255,255,255,0.14);
      --text:      #e4eaf4;
      --text2:     #8a95a8;
      --text3:     #5c6475;
      --orange:    #f06a35;
      --orange2:   #ff8c5a;
      --orange-bg: rgba(240,106,53,0.12);
      --green:     #3dd68c;
      --green-bg:  rgba(61,214,140,0.10);
      --blue:      #5b9cf6;
      --blue-bg:   rgba(91,156,246,0.10);
      --purple:    #a78bfa;
      --yellow:    #f5c842;
      --red:       #f87171;
    }

    html, body { height: 100%; overflow: hidden; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      color: var(--text);
      display: flex;
      flex-direction: column;
      font-size: 12px;
    }

    /* ── TOP BAR ─────────────────────────────── */
    .topbar {
      height: 48px;
      background: var(--bg2);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 16px;
      flex-shrink: 0;
      z-index: 100;
    }

    .tb-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .tb-logo {
      width: 28px; height: 28px;
      background: var(--orange);
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 11px; color: white;
      letter-spacing: -0.3px;
    }

    .tb-title {
      font-size: 12px;
      font-weight: 600;
      color: var(--text);
      letter-spacing: -0.2px;
    }

    .tb-sep {
      width: 1px; height: 18px;
      background: var(--border2);
      margin: 0 4px;
    }

    .tb-subtitle {
      font-size: 11px;
      color: var(--text3);
    }

    .tb-right {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .gps-pill {
      display: flex; align-items: center; gap: 6px;
      background: var(--green-bg);
      border: 1px solid rgba(61,214,140,0.2);
      border-radius: 6px;
      padding: 4px 10px;
      font-size: 10.5px;
      font-weight: 500;
      color: var(--green);
      font-family: 'DM Mono', monospace;
    }

    .gps-dot {
      width: 6px; height: 6px;
      background: var(--green);
      border-radius: 50%;
      animation: blink 1.6s ease-in-out infinite;
    }

    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

    /* ── MAIN LAYOUT ─────────────────────────── */
    .workspace {
      display: flex;
      flex: 1;
      overflow: hidden;
    }

    /* ── LEFT PANEL ──────────────────────────── */
    .left-panel {
      width: 240px;
      background: var(--bg2);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      overflow: hidden;
      flex-shrink: 0;
    }

    .panel-section {
      border-bottom: 1px solid var(--border);
      padding: 14px;
    }

    .section-label {
      font-size: 9.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--text3);
      margin-bottom: 10px;
      display: flex; align-items: center; gap: 5px;
    }

    .section-label .dot {
      width: 4px; height: 4px;
      background: var(--orange);
      border-radius: 50%;
    }

    /* Selects */
    .field-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .field-label {
      font-size: 10px;
      color: var(--text3);
      font-weight: 500;
    }

    .sel-wrap {
      position: relative;
    }

    .sel-wrap::after {
      content: '▾';
      position: absolute;
      right: 10px; top: 50%;
      transform: translateY(-50%);
      font-size: 10px;
      color: var(--text3);
      pointer-events: none;
    }

    select, input[type="text"] {
      width: 100%;
      background: var(--bg3);
      border: 1px solid var(--border2);
      border-radius: 7px;
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      font-size: 11.5px;
      padding: 7px 28px 7px 10px;
      appearance: none;
      outline: none;
      transition: border-color 0.15s;
      cursor: pointer;
    }

    select:hover, select:focus {
      border-color: var(--orange);
    }

    select option { background: var(--bg3); }

    .btn-primary {
      width: 100%;
      background: var(--orange);
      color: white;
      border: none;
      border-radius: 7px;
      padding: 8px 12px;
      font-family: 'DM Sans', sans-serif;
      font-weight: 600;
      font-size: 11.5px;
      cursor: pointer;
      transition: background 0.15s, transform 0.1s;
      letter-spacing: 0.2px;
      margin-top: 8px;
    }

    .btn-primary:hover { background: var(--orange2); }
    .btn-primary:active { transform: scale(0.98); }

    /* Floor tabs */
    .floor-tabs {
      display: flex;
      gap: 4px;
      background: var(--bg3);
      border-radius: 8px;
      padding: 3px;
    }

    .floor-tab {
      flex: 1;
      background: none;
      border: none;
      border-radius: 6px;
      color: var(--text2);
      font-family: 'DM Sans', sans-serif;
      font-size: 11px;
      font-weight: 600;
      padding: 6px 0;
      cursor: pointer;
      transition: all 0.15s;
    }

    .floor-tab.active {
      background: var(--bg2);
      color: var(--text);
      box-shadow: 0 1px 4px rgba(0,0,0,0.4);
    }

    /* Layers list */
    .layer-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 6px 0;
    }

    .layer-left {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 11.5px;
      color: var(--text2);
    }

    .layer-icon {
      width: 22px; height: 22px;
      border-radius: 5px;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px;
    }

    /* Toggle */
    .toggle {
      position: relative;
      width: 32px; height: 17px;
    }
    .toggle input { opacity: 0; width: 0; height: 0; }
    .toggle-track {
      position: absolute; inset: 0;
      background: var(--bg3);
      border: 1px solid var(--border2);
      border-radius: 9px;
      cursor: pointer;
      transition: background 0.15s, border-color 0.15s;
    }
    .toggle-track::before {
      content: '';
      position: absolute;
      width: 11px; height: 11px;
      left: 2px; top: 2px;
      background: var(--text3);
      border-radius: 50%;
      transition: transform 0.15s, background 0.15s;
    }
    .toggle input:checked + .toggle-track {
      background: var(--orange-bg);
      border-color: var(--orange);
    }
    .toggle input:checked + .toggle-track::before {
      transform: translateX(15px);
      background: var(--orange);
    }

    /* GPS button */
    .btn-gps {
      width: 100%;
      background: var(--green-bg);
      border: 1px solid rgba(61,214,140,0.25);
      border-radius: 7px;
      color: var(--green);
      font-family: 'DM Sans', sans-serif;
      font-size: 11px;
      font-weight: 600;
      padding: 7px 10px;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      gap: 6px;
      transition: background 0.15s;
    }
    .btn-gps:hover { background: rgba(61,214,140,0.18); }

    .gps-coords {
      font-family: 'DM Mono', monospace;
      font-size: 9.5px;
      color: var(--text3);
      margin-top: 7px;
      line-height: 1.6;
    }

    /* Help tip */
    .tip-box {
      margin: 12px 14px 0;
      background: var(--bg3);
      border: 1px solid var(--border2);
      border-radius: 8px;
      padding: 10px;
      font-size: 10.5px;
      color: var(--text3);
      line-height: 1.6;
    }
    .tip-box strong { color: var(--text2); }

    /* ── CENTER VIEWPORT ─────────────────────── */
    .viewport {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      position: relative;
    }

    /* View switcher toolbar */
    .view-toolbar {
      position: absolute;
      top: 14px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 50;
      display: flex;
      background: var(--panel);
      border: 1px solid var(--border2);
      border-radius: 9px;
      padding: 3px;
      gap: 3px;
    }

    .view-tab {
      background: none;
      border: none;
      border-radius: 6px;
      color: var(--text3);
      font-family: 'DM Sans', sans-serif;
      font-size: 11px;
      font-weight: 600;
      padding: 5px 18px;
      cursor: pointer;
      transition: all 0.15s;
      letter-spacing: 0.3px;
    }

    .view-tab.active {
      background: var(--bg3);
      color: var(--text);
    }

    /* Canvas area */
    .canvas-wrap {
      flex: 1;
      position: relative;
      overflow: hidden;
      background: var(--bg);
    }

    .view2d-container {
      position: absolute; inset: 0;
      display: flex; align-items: center; justify-content: center;
    }

    .view2d-container.hide { display: none; }

    #floorSvg {
      width: 100%;
      height: 100%;
    }

    .view3d-container {
      position: absolute; inset: 0;
      display: none;
    }

    .view3d-container.active { display: block; }

    /* Fullscreen btn */
    .btn-fullscreen {
      position: absolute;
      top: 14px; right: 14px;
      z-index: 50;
      background: var(--panel);
      border: 1px solid var(--border2);
      border-radius: 7px;
      color: var(--text2);
      font-size: 11px;
      font-family: 'DM Sans', sans-serif;
      font-weight: 600;
      padding: 5px 12px;
      cursor: pointer;
      transition: border-color 0.15s;
    }
    .btn-fullscreen:hover { border-color: var(--orange); color: var(--orange); }

    /* Route steps strip */
    .route-strip {
      display: none;
      background: var(--bg2);
      border-top: 1px solid var(--border);
      padding: 10px 16px;
      align-items: center;
      gap: 16px;
      flex-shrink: 0;
    }
    .route-strip.show { display: flex; }

    .route-steps {
      display: flex;
      align-items: center;
      gap: 6px;
      flex-wrap: wrap;
      flex: 1;
    }

    .step-chip {
      display: flex; align-items: center; gap: 6px;
      font-size: 10.5px;
      color: var(--text2);
    }

    .step-num {
      width: 18px; height: 18px;
      background: var(--orange);
      border-radius: 5px;
      display: flex; align-items: center; justify-content: center;
      font-size: 9px;
      font-weight: 700;
      color: white;
      flex-shrink: 0;
    }

    .step-arrow {
      color: var(--text3);
      font-size: 10px;
    }

    .route-metrics {
      display: flex; gap: 12px;
      font-size: 10.5px;
      font-family: 'DM Mono', monospace;
      color: var(--text3);
      flex-shrink: 0;
    }

    .metric-item {
      display: flex; align-items: center; gap: 4px;
    }

    .metric-val {
      color: var(--orange);
      font-weight: 500;
    }

    /* ── RIGHT PANEL ──────────────────────────── */
    .right-panel {
      width: 224px;
      background: var(--bg2);
      border-left: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      flex-shrink: 0;
    }

    /* Legend grid */
    .legend-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 5px;
      padding: 14px;
    }

    .legend-item {
      display: flex; align-items: center; gap: 6px;
      font-size: 10.5px;
      color: var(--text2);
    }

    .legend-swatch {
      width: 10px; height: 10px;
      border-radius: 3px;
      flex-shrink: 0;
      opacity: 0.85;
    }

    /* Selected info card */
    .sel-card {
      display: none;
      margin: 0 12px 12px;
      background: var(--bg3);
      border: 1px solid var(--border2);
      border-radius: 10px;
      padding: 12px;
    }
    .sel-card.show { display: block; }

    .sel-id {
      font-family: 'DM Mono', monospace;
      font-size: 9.5px;
      color: var(--orange);
      margin-bottom: 3px;
    }

    .sel-name {
      font-size: 13px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 2px;
    }

    .sel-type {
      font-size: 10.5px;
      color: var(--text3);
      margin-bottom: 10px;
    }

    .sel-actions {
      display: flex;
      gap: 6px;
    }

    .btn-sel {
      flex: 1;
      border: none;
      border-radius: 6px;
      font-family: 'DM Sans', sans-serif;
      font-size: 10px;
      font-weight: 600;
      padding: 6px 4px;
      cursor: pointer;
      transition: opacity 0.15s;
    }
    .btn-sel:hover { opacity: 0.85; }

    .btn-start { background: rgba(61,214,140,0.15); color: var(--green); border: 1px solid rgba(61,214,140,0.25); }
    .btn-end   { background: rgba(240,106,53,0.15); color: var(--orange2); border: 1px solid rgba(240,106,53,0.25); }

    /* 3D status card */
    .status-card {
      margin: 0 12px 12px;
      background: var(--bg3);
      border: 1px solid var(--border2);
      border-radius: 10px;
      padding: 12px;
    }

    .status-title {
      font-size: 10px;
      font-weight: 700;
      color: var(--text2);
      text-transform: uppercase;
      letter-spacing: 0.7px;
      margin-bottom: 6px;
    }

    .status-text {
      font-size: 10.5px;
      color: var(--text3);
      margin-bottom: 8px;
      line-height: 1.5;
    }

    .progress-track {
      height: 3px;
      background: rgba(255,255,255,0.06);
      border-radius: 2px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      width: 0%;
      background: var(--orange);
      border-radius: 2px;
      transition: width 0.3s;
    }

    /* ── TOAST ───────────────────────────────── */
    .toast {
      position: fixed;
      bottom: 24px;
      left: 50%;
      transform: translateX(-50%) translateY(8px);
      background: var(--panel);
      border: 1px solid var(--border2);
      color: var(--text);
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 11.5px;
      font-weight: 500;
      opacity: 0;
      transition: opacity 0.2s, transform 0.2s;
      z-index: 2000;
      white-space: nowrap;
      pointer-events: none;
    }
    .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

    /* ── LOADING OVERLAY ─────────────────────── */
    .load-overlay {
      position: absolute; inset: 0;
      background: var(--bg2);
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 12px; z-index: 30;
    }

    .spinner {
      width: 32px; height: 32px;
      border: 2px solid var(--border2);
      border-top-color: var(--orange);
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .load-label {
      font-size: 11px;
      color: var(--text3);
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--bg3); border-radius: 3px; }
  </style>
</head>
<body>

<!-- ── TOP BAR ──────────────────────────────────── -->
<div class="topbar">
  <div class="tb-left">
    <div class="tb-logo">RP</div>
    <span class="tb-title">RP Kigali Smart Campus</span>
    <div class="tb-sep"></div>
    <span class="tb-subtitle">3D GeoPortal · Live GPS</span>
  </div>
  <div class="tb-right">
    <div class="gps-pill">
      <span class="gps-dot"></span>
      <span id="locText">Waiting for GPS</span>
    </div>
  </div>
</div>

<!-- ── WORKSPACE ─────────────────────────────────── -->
<div class="workspace">

  <!-- LEFT PANEL -->
  <div class="left-panel">

    <div class="panel-section">
      <div class="section-label"><span class="dot"></span>Directions</div>
      <div class="field-group">
        <div class="field-label">From</div>
        <div class="sel-wrap"><select id="fromSel"></select></div>
        <div class="field-label" style="margin-top:4px;">To</div>
        <div class="sel-wrap"><select id="toSel"></select></div>
      </div>
      <button class="btn-primary" id="calcRouteBtn">Get Route →</button>
    </div>

    <div class="panel-section">
      <div class="section-label"><span class="dot"></span>Floor</div>
      <div class="floor-tabs">
        <button class="floor-tab active" id="floorL0Btn">Ground · L0</button>
        <button class="floor-tab" id="floorL1Btn">First · L1</button>
      </div>
    </div>

    <div class="panel-section">
      <div class="section-label"><span class="dot"></span>Layers</div>
      <div class="layer-row">
        <div class="layer-left">
          <div class="layer-icon" style="background:rgba(91,156,246,0.12);">🧱</div>
          Rooms
        </div>
        <label class="toggle"><input type="checkbox" id="toggleRooms" checked><span class="toggle-track"></span></label>
      </div>
      <div class="layer-row">
        <div class="layer-left">
          <div class="layer-icon" style="background:rgba(61,214,140,0.12);">🟢</div>
          Route Path
        </div>
        <label class="toggle"><input type="checkbox" id="toggleRoute" checked><span class="toggle-track"></span></label>
      </div>
      <div class="layer-row">
        <div class="layer-left">
          <div class="layer-icon" style="background:rgba(240,106,53,0.12);">🏛️</div>
          3D Model
        </div>
        <label class="toggle"><input type="checkbox" id="toggleModel3d" checked><span class="toggle-track"></span></label>
      </div>
    </div>

    <div class="panel-section">
      <div class="section-label"><span class="dot"></span>Live Location</div>
      <button class="btn-gps" id="requestLocationBtn">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
        Enable GPS
      </button>
      <div class="gps-coords" id="gpsDetails">No GPS data yet</div>
    </div>

    <div class="tip-box">
      <strong>3D Controls</strong><br>
      Drag to rotate · Right-click pan · Scroll zoom · Click room to select
    </div>

  </div>

  <!-- CENTER VIEWPORT -->
  <div class="viewport">

    <div class="view-toolbar">
      <button class="view-tab active" id="tab2dBtn">2D Map</button>
      <button class="view-tab" id="tab3dBtn">3D View</button>
    </div>

    <button class="btn-fullscreen" id="fullscreenBtn">⛶ Fullscreen</button>

    <div class="canvas-wrap" id="canvasWrap">
      <!-- 2D -->
      <div class="view2d-container" id="view2dContainer">
        <svg id="floorSvg" viewBox="0 0 600 500"></svg>
      </div>
      <!-- 3D -->
      <div class="view3d-container" id="view3dContainer"></div>
    </div>

    <!-- Route strip -->
    <div class="route-strip" id="routePanel">
      <div class="route-steps" id="stepListContainer"></div>
      <div class="route-metrics" id="routeMetrics"></div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="right-panel">

    <div class="panel-section">
      <div class="section-label"><span class="dot"></span>Legend</div>
      <div class="legend-grid">
        <div class="legend-item"><div class="legend-swatch" style="background:#5b9cf6"></div>Classroom</div>
        <div class="legend-item"><div class="legend-swatch" style="background:#a78bfa"></div>Office</div>
        <div class="legend-item"><div class="legend-swatch" style="background:#f5c842"></div>Study</div>
        <div class="legend-item"><div class="legend-swatch" style="background:#3dd68c"></div>Lab</div>
        <div class="legend-item"><div class="legend-swatch" style="background:#f87171"></div>Toilet</div>
        <div class="legend-item"><div class="legend-swatch" style="background:#f06a35"></div>Stairs</div>
      </div>
    </div>

    <div class="sel-card" id="selInfo">
      <div class="sel-id" id="selInfoType"></div>
      <div class="sel-name" id="selInfoName">—</div>
      <div class="sel-type" id="selFloor"></div>
      <div class="sel-actions">
        <button class="btn-sel btn-start" id="setFromBtn">▶ Set Start</button>
        <button class="btn-sel btn-end" id="setToBtn">⬛ Set End</button>
      </div>
    </div>

    <div class="status-card" id="modelStatus">
      <div class="status-title">3D Model (GLB)</div>
      <div class="status-text" id="msText">Waiting for scene.glb…</div>
      <div class="progress-track"><div class="progress-fill" id="msFillBar"></div></div>
    </div>

  </div>
</div>

<div class="toast" id="toastMsg"></div>

<!-- ── SCRIPTS ──────────────────────────────────── -->
<script type="module">
  import * as THREE from 'three';
  import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
  import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

  // ── ROOM DATA ──────────────────────────────────
  const ROOMS = {
    GF01:     { id:'GF01',     name:'Main Reception',   floor:'L0', type:'reception', color:'#2a3040', accent:'#8a95a8', x:210,y:270,w:190,h:100 },
    GF02:     { id:'GF02',     name:'Study Area',        floor:'L0', type:'study',     color:'#2e2a18', accent:'#f5c842', x:210,y:40,w:220,h:160 },
    GF03:     { id:'GF03',     name:'Computer Lab',      floor:'L0', type:'lab',       color:'#1a2e24', accent:'#3dd68c', x:30,y:40,w:170,h:150 },
    TOILETS:  { id:'TOILETS',  name:'Washrooms',         floor:'L0', type:'toilet',    color:'#2e1a22', accent:'#f87171', x:30,y:240,w:140,h:100 },
    STAIRS_L0:{ id:'STAIRS_L0',name:'Staircase',         floor:'L0', type:'stairs',    color:'#2a1f12', accent:'#f06a35', x:430,y:190,w:100,h:145 },
    FF01:     { id:'FF01',     name:'Classroom 101',     floor:'L1', type:'classroom', color:'#1a2238', accent:'#5b9cf6', x:30,y:30,w:140,h:160 },
    FF02:     { id:'FF02',     name:'Classroom 102',     floor:'L1', type:'classroom', color:'#1a2238', accent:'#5b9cf6', x:180,y:30,w:140,h:160 },
    FF03:     { id:'FF03',     name:'Staff Office',      floor:'L1', type:'office',    color:'#221a38', accent:'#a78bfa', x:330,y:30,w:140,h:160 },
    STAIRS_L1:{ id:'STAIRS_L1',name:'Staircase Upper',   floor:'L1', type:'stairs',    color:'#2a1f12', accent:'#f06a35', x:470,y:50,w:90,h:130 }
  };

  const ROUTE_DB = {
    'GF01-FF02': { steps:['Reception → corridor','Take stairs to L1','Arrive Classroom 102'], time:'2 min', dist:'48 m', paths:{ L0:'M305 320 L305 250 L430 250 L430 260', L1:'M430 300 L430 250 L250 250 L250 110' } },
    'GF01-FF01': { steps:['Reception → stairs','Climb to L1','Classroom 101'], time:'1.8 min', dist:'42 m', paths:{ L0:'M305 320 L305 250 L430 250', L1:'M430 300 L430 250 L100 250 L100 110' } },
    'GF02-FF03': { steps:['Study area → stairs','Upstairs','Office L1'], time:'2.2 min', dist:'53 m', paths:{ L0:'M320 120 L430 120 L430 250', L1:'M430 300 L430 200 L400 200 L400 110' } }
  };

  function getRouteKey(a,b) {
    if(ROUTE_DB[`${a}-${b}`]) return { key:`${a}-${b}`, rev:false };
    if(ROUTE_DB[`${b}-${a}`]) return { key:`${b}-${a}`, rev:true };
    return null;
  }

  // ── STATE ──────────────────────────────────────
  let currentFloor = 'L0', currentView = '2d';
  let showRooms = true, showRouteLayer = true, showModel3d = true;
  let fromRoom = 'GF01', toRoom = 'FF02', selectedRoom = null, activeRoute = null;
  let scene3d, camera3d, renderer3d, controls3d, raycaster3d, mouse3d;
  let gltfModel = null, threeInitialized = false, selectableObjects = [];
  let currentPosition = null, watchId = null;

  // ── TOAST ──────────────────────────────────────
  const toast = (msg) => {
    const t = document.getElementById('toastMsg');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2500);
  };

  // ── 2D RENDER ──────────────────────────────────
  function render2DMap() {
    const svg = document.getElementById('floorSvg');
    if(!svg) return;
    svg.innerHTML = '';

    // Background grid feel
    const bgRect = makeSvg('rect', { width:'600', height:'500', fill:'#1a1f26' });
    svg.appendChild(bgRect);

    // Subtle dot grid
    const defs = makeSvg('defs');
    const pattern = makeSvg('pattern', { id:'dotgrid', x:'0', y:'0', width:'20', height:'20', patternUnits:'userSpaceOnUse' });
    const dotPt = makeSvg('circle', { cx:'1', cy:'1', r:'0.8', fill:'rgba(255,255,255,0.06)' });
    pattern.appendChild(dotPt);
    defs.appendChild(pattern);
    svg.appendChild(defs);
    const gridBg = makeSvg('rect', { width:'600', height:'500', fill:'url(#dotgrid)' });
    svg.appendChild(gridBg);

    // Corridor
    const corridorAttrs = currentFloor === 'L0'
      ? { x:'200', y:'200', width:'130', height:'90' }
      : { x:'200', y:'200', width:'200', height:'70' };
    const corridor = makeSvg('rect', { ...corridorAttrs, fill:'rgba(255,255,255,0.03)', stroke:'rgba(255,255,255,0.06)', 'stroke-width':'1' });
    svg.appendChild(corridor);

    if(showRooms) {
      Object.values(ROOMS).filter(r => r.floor === currentFloor).forEach(room => {
        const isSel  = (selectedRoom === room.id);
        const isFrom = (fromRoom === room.id);
        const isTo   = (toRoom === room.id);

        const g = makeSvg('g');
        g.style.cursor = 'pointer';
        g.setAttribute('onclick', `window.dispatchEvent(new CustomEvent('roomSelect2d', {detail:{id:'${room.id}'}}))`);

        // Shadow-ish bg
        const shadow = makeSvg('rect', {
          x: room.x+2, y: room.y+2, width: room.w, height: room.h, rx:'8',
          fill:'rgba(0,0,0,0.25)'
        });
        g.appendChild(shadow);

        // Main room rect
        let strokeColor = 'rgba(255,255,255,0.08)';
        let strokeW = '1';
        let fill = room.color;
        if(isSel)  { strokeColor = '#f5c842'; strokeW = '2'; fill = '#2e2818'; }
        if(isFrom) { strokeColor = '#3dd68c'; strokeW = '2'; }
        if(isTo)   { strokeColor = '#f06a35'; strokeW = '2'; }

        const rect = makeSvg('rect', {
          x:room.x, y:room.y, width:room.w, height:room.h, rx:'8',
          fill, stroke:strokeColor, 'stroke-width':strokeW
        });
        g.appendChild(rect);

        // Accent bar top
        const bar = makeSvg('rect', {
          x:room.x, y:room.y, width:room.w, height:'3', rx:'8',
          fill: isSel ? '#f5c842' : isFrom ? '#3dd68c' : isTo ? '#f06a35' : room.accent,
          opacity: isSel||isFrom||isTo ? '1' : '0.6'
        });
        g.appendChild(bar);

        // Room ID
        const id = makeSvg('text', {
          x:room.x+room.w/2, y:room.y+room.h/2-6,
          'text-anchor':'middle', 'font-size':'10', 'font-weight':'700',
          fill: room.accent, 'font-family':'DM Mono, monospace'
        });
        id.textContent = room.id;
        g.appendChild(id);

        // Room name
        const name = makeSvg('text', {
          x:room.x+room.w/2, y:room.y+room.h/2+10,
          'text-anchor':'middle', 'font-size':'9', 'font-weight':'400',
          fill:'rgba(255,255,255,0.4)', 'font-family':'DM Sans, sans-serif'
        });
        name.textContent = room.name;
        g.appendChild(name);

        svg.appendChild(g);
      });
    }

    // Route path
    if(showRouteLayer && activeRoute?.paths?.[currentFloor]) {
      const path = makeSvg('path', {
        d: activeRoute.paths[currentFloor],
        stroke:'#f06a35', 'stroke-width':'2.5',
        'stroke-dasharray':'6 4', fill:'none',
        'stroke-linecap':'round', opacity:'0.9'
      });
      svg.appendChild(path);

      // Animated route dot
      const animCircle = makeSvg('circle', { r:'4', fill:'#f06a35' });
      const animTag = makeSvg('animateMotion', { dur:'2.4s', repeatCount:'indefinite' });
      const mPath = makeSvg('mpath');
      path.setAttribute('id','routePath');
      mPath.setAttributeNS('http://www.w3.org/1999/xlink','href','#routePath');
      animTag.appendChild(mPath);
      animCircle.appendChild(animTag);
      svg.appendChild(animCircle);
    }

    // GPS marker
    if(currentPosition && currentFloor === 'L0') {
      const ring = makeSvg('circle', { cx:'310', cy:'320', r:'14', fill:'rgba(61,214,140,0.12)', stroke:'rgba(61,214,140,0.3)', 'stroke-width':'1' });
      svg.appendChild(ring);
      const dot = makeSvg('circle', { cx:'310', cy:'320', r:'6', fill:'#3dd68c', stroke:'#1a1f26', 'stroke-width':'2' });
      svg.appendChild(dot);
      const lbl = makeSvg('text', { x:'322', y:'317', 'font-size':'8', 'font-weight':'700', fill:'#3dd68c', 'font-family':'DM Mono, monospace' });
      lbl.textContent = 'YOU';
      svg.appendChild(lbl);
    }
  }

  function makeSvg(tag, attrs = {}) {
    const el = document.createElementNS('http://www.w3.org/2000/svg', tag);
    for(const [k,v] of Object.entries(attrs)) el.setAttribute(k,v);
    return el;
  }

  function selectRoomById(id) {
    selectedRoom = id;
    const room = ROOMS[id];
    document.getElementById('selInfo').classList.add('show');
    document.getElementById('selInfoType').textContent = `${room.id} · ${room.type}`;
    document.getElementById('selInfoName').textContent = room.name;
    document.getElementById('selFloor').textContent = `Floor ${room.floor}`;
    render2DMap();
    highlight3DObject(id);
    toast(`Selected: ${room.name}`);
  }

  // ── 3D CORE ────────────────────────────────────
  async function init3D() {
    if(threeInitialized) return;
    const container = document.getElementById('view3dContainer');
    container.innerHTML = `<div class="load-overlay"><div class="spinner"></div><div class="load-label">Loading 3D environment…</div></div>`;

    const w = container.clientWidth, h = container.clientHeight;
    scene3d = new THREE.Scene();
    scene3d.background = new THREE.Color(0x1a1f26);
    scene3d.fog = new THREE.FogExp2(0x1a1f26, 0.012);

    camera3d = new THREE.PerspectiveCamera(45, w/h, 0.1, 500);
    camera3d.position.set(6, 5, 10);
    camera3d.lookAt(0, 1, 0);

    renderer3d = new THREE.WebGLRenderer({ antialias:true });
    renderer3d.setSize(w, h);
    renderer3d.shadowMap.enabled = true;
    renderer3d.setPixelRatio(window.devicePixelRatio);
    container.appendChild(renderer3d.domElement);

    controls3d = new OrbitControls(camera3d, renderer3d.domElement);
    controls3d.enableDamping = true;
    controls3d.dampingFactor = 0.06;
    controls3d.rotateSpeed = 1.2;
    controls3d.target.set(0, 1.2, 0);

    raycaster3d = new THREE.Raycaster();
    mouse3d = new THREE.Vector2();

    // Lighting
    scene3d.add(new THREE.AmbientLight(0x3a4a6a, 0.8));
    const main = new THREE.DirectionalLight(0xfff0d8, 1.2);
    main.position.set(5, 12, 6);
    main.castShadow = true;
    main.shadow.mapSize.set(1024,1024);
    scene3d.add(main);
    scene3d.add(new THREE.PointLight(0xf06a35, 0.3, 20));

    // Grid
    const grid = new THREE.GridHelper(28, 24, 0x2a3040, 0x212630);
    grid.position.y = -0.1;
    scene3d.add(grid);

    // Load GLB
    const loader = new GLTFLoader();
    const statusSpan = document.getElementById('msText');
    const fillBar = document.getElementById('msFillBar');

    loader.load('./scene.glb',
      (gltf) => {
        gltfModel = gltf.scene;
        gltfModel.traverse(child => {
          if(child.isMesh) {
            child.castShadow = true;
            child.receiveShadow = true;
            selectableObjects.push(child);
            if(child.name && ROOMS[child.name]) child.userData = { id: child.name };
          }
        });
        const box = new THREE.Box3().setFromObject(gltfModel);
        const size = box.getSize(new THREE.Vector3());
        const center = box.getCenter(new THREE.Vector3());
        const maxDim = Math.max(size.x, size.y, size.z);
        const scale = 5.5 / maxDim;
        gltfModel.scale.setScalar(scale);
        gltfModel.position.sub(center.multiplyScalar(scale));
        gltfModel.position.y = -0.05;
        gltfModel.visible = showModel3d;
        scene3d.add(gltfModel);
        statusSpan.textContent = '✓ scene.glb loaded — click objects to select';
        fillBar.style.width = '100%';
        toast('3D model loaded');
        const ov = document.getElementById('load3dOverlay');
        if(ov) ov.remove();
        const overlayEl = container.querySelector('.load-overlay');
        if(overlayEl) overlayEl.remove();
      },
      (xhr) => {
        if(xhr.total) {
          const pct = (xhr.loaded/xhr.total*100).toFixed(0);
          statusSpan.textContent = `Loading GLB… ${pct}%`;
          fillBar.style.width = `${pct}%`;
        }
      },
      (error) => {
        console.warn('GLB not found:', error);
        statusSpan.textContent = '⚠ scene.glb not found — place file in same directory';
        fillBar.style.width = '100%';
        const overlayEl = container.querySelector('.load-overlay');
        if(overlayEl) overlayEl.remove();
      }
    );

    renderer3d.domElement.addEventListener('click', (event) => {
      const rect = renderer3d.domElement.getBoundingClientRect();
      mouse3d.x = ((event.clientX - rect.left)/rect.width)*2-1;
      mouse3d.y = -((event.clientY - rect.top)/rect.height)*2+1;
      raycaster3d.setFromCamera(mouse3d, camera3d);
      const hits = raycaster3d.intersectObjects(selectableObjects, true);
      if(hits.length > 0) {
        let h = hits[0].object;
        while(h && !h.userData?.id && h.name && !ROOMS[h.name]) h = h.parent;
        const roomId = h?.userData?.id || h?.name;
        if(roomId && ROOMS[roomId]) selectRoomById(roomId);
        else if(roomId) toast(`Selected: ${roomId}`);
      }
    });

    function animate() {
      requestAnimationFrame(animate);
      controls3d.update();
      renderer3d.render(scene3d, camera3d);
    }
    animate();

    window.addEventListener('resize', () => {
      if(renderer3d && container.clientWidth) {
        camera3d.aspect = container.clientWidth/container.clientHeight;
        camera3d.updateProjectionMatrix();
        renderer3d.setSize(container.clientWidth, container.clientHeight);
      }
    });
    threeInitialized = true;
  }

  function highlight3DObject(roomId) {
    if(!scene3d || !selectableObjects.length) return;
    selectableObjects.forEach(obj => {
      if(obj.material) obj.material.emissiveIntensity = 0;
    });
    const t = selectableObjects.find(o => o.userData?.id === roomId || o.name === roomId);
    if(t?.material) { t.material.emissiveIntensity = 0.5; t.material.color?.setHex(0xf06a35); }
  }

  function toggleModelVisibility(v) { showModel3d = v; if(gltfModel) gltfModel.visible = v; }

  // ── GPS ────────────────────────────────────────
  function requestRealLocation() {
    if(!navigator.geolocation) { toast('Geolocation not supported'); return; }
    toast('Requesting GPS access…');
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        currentPosition = { lat:pos.coords.latitude, lng:pos.coords.longitude, accuracy:pos.coords.accuracy };
        document.getElementById('locText').textContent = `${currentPosition.lat.toFixed(5)}, ${currentPosition.lng.toFixed(5)}`;
        document.getElementById('gpsDetails').innerHTML = `${currentPosition.lat.toFixed(6)}°, ${currentPosition.lng.toFixed(6)}°<br>±${Math.round(currentPosition.accuracy)}m accuracy`;
        toast('GPS active — location acquired');
        render2DMap();
        if(watchId) navigator.geolocation.clearWatch(watchId);
        watchId = navigator.geolocation.watchPosition(
          (np) => {
            currentPosition = { lat:np.coords.latitude, lng:np.coords.longitude, accuracy:np.coords.accuracy };
            document.getElementById('locText').textContent = `${currentPosition.lat.toFixed(5)}, ${currentPosition.lng.toFixed(5)}`;
            document.getElementById('gpsDetails').innerHTML = `${currentPosition.lat.toFixed(6)}°, ${currentPosition.lng.toFixed(6)}°<br>±${Math.round(currentPosition.accuracy)}m accuracy`;
            render2DMap();
          },
          (e) => console.warn(e),
          { enableHighAccuracy:true, maximumAge:2000, timeout:10000 }
        );
      },
      (e) => { toast(`GPS error: ${e.message}`); document.getElementById('gpsDetails').textContent = 'Location denied.'; },
      { enableHighAccuracy:true, timeout:15000 }
    );
  }

  // ── VIEW SWITCHING ──────────────────────────────
  function setView(view) {
    currentView = view;
    const v2 = document.getElementById('view2dContainer');
    const v3 = document.getElementById('view3dContainer');
    if(view === '2d') {
      v2.classList.remove('hide'); v3.classList.remove('active'); v3.style.display = 'none'; v2.style.display = 'flex';
      document.getElementById('tab2dBtn').classList.add('active');
      document.getElementById('tab3dBtn').classList.remove('active');
      render2DMap();
    } else {
      v2.classList.add('hide'); v2.style.display = 'none'; v3.classList.add('active'); v3.style.display = 'block';
      document.getElementById('tab3dBtn').classList.add('active');
      document.getElementById('tab2dBtn').classList.remove('active');
      if(!threeInitialized) init3D();
      else { setTimeout(()=>{ if(renderer3d&&v3.clientWidth) renderer3d.setSize(v3.clientWidth,v3.clientHeight); },50); }
    }
  }

  function setFloor(floor) {
    currentFloor = floor;
    document.getElementById('floorL0Btn').classList.toggle('active', floor==='L0');
    document.getElementById('floorL1Btn').classList.toggle('active', floor==='L1');
    render2DMap();
    toast(`Floor: ${floor==='L0' ? 'Ground' : 'First'}`);
  }

  function calculateRoute() {
    const from = document.getElementById('fromSel').value;
    const to   = document.getElementById('toSel').value;
    if(!from || !to || from===to) { toast('Select two different rooms'); return; }
    fromRoom = from; toRoom = to;
    const rk = getRouteKey(from,to);
    activeRoute = rk
      ? { ...ROUTE_DB[rk.key], steps: rk.rev ? [...ROUTE_DB[rk.key].steps].reverse() : ROUTE_DB[rk.key].steps }
      : { steps:[`${ROOMS[from].name}`, 'Walk via corridor', `${ROOMS[to].name}`], time:'~2 min', dist:'~40 m', paths:{} };

    const stepsHtml = activeRoute.steps.map((s,i) =>
      `<div class="step-chip"><div class="step-num">${i+1}</div><span>${s}</span></div>${i<activeRoute.steps.length-1?'<span class="step-arrow">›</span>':''}`
    ).join('');
    document.getElementById('stepListContainer').innerHTML = stepsHtml;
    document.getElementById('routeMetrics').innerHTML = `
      <div class="metric-item">⏱ <span class="metric-val">${activeRoute.time}</span></div>
      <div class="metric-item">📏 <span class="metric-val">${activeRoute.dist}</span></div>`;
    document.getElementById('routePanel').classList.add('show');
    render2DMap();
    toast(`Route: ${ROOMS[from].name} → ${ROOMS[to].name}`);
  }

  function populateSelects() {
    const fs = document.getElementById('fromSel'), ts = document.getElementById('toSel');
    fs.innerHTML = ts.innerHTML = '';
    Object.values(ROOMS).forEach(r => {
      const opt = `<option value="${r.id}">${r.name} (${r.floor})</option>`;
      fs.innerHTML += opt; ts.innerHTML += opt;
    });
    fs.value = fromRoom; ts.value = toRoom;
  }

  // ── EVENTS ─────────────────────────────────────
  window.addEventListener('roomSelect2d', e => selectRoomById(e.detail.id));
  document.getElementById('tab2dBtn').onclick = () => setView('2d');
  document.getElementById('tab3dBtn').onclick = () => setView('3d');
  document.getElementById('floorL0Btn').onclick = () => setFloor('L0');
  document.getElementById('floorL1Btn').onclick = () => setFloor('L1');
  document.getElementById('calcRouteBtn').onclick = calculateRoute;
  document.getElementById('requestLocationBtn').onclick = requestRealLocation;
  document.getElementById('fullscreenBtn').onclick = () => { if(document.documentElement.requestFullscreen) document.documentElement.requestFullscreen(); };
  document.getElementById('toggleRooms').addEventListener('change', e => { showRooms = e.target.checked; render2DMap(); });
  document.getElementById('toggleRoute').addEventListener('change', e => { showRouteLayer = e.target.checked; render2DMap(); });
  document.getElementById('toggleModel3d').addEventListener('change', e => toggleModelVisibility(e.target.checked));
  document.getElementById('setFromBtn').onclick = () => {
    if(selectedRoom) { fromRoom = selectedRoom; document.getElementById('fromSel').value = selectedRoom; render2DMap(); toast(`Start → ${ROOMS[selectedRoom].name}`); }
    else toast('Select a room first');
  };
  document.getElementById('setToBtn').onclick = () => {
    if(selectedRoom) { toRoom = selectedRoom; document.getElementById('toSel').value = selectedRoom; render2DMap(); toast(`End → ${ROOMS[selectedRoom].name}`); }
    else toast('Select a room first');
  };

  // ── INIT ───────────────────────────────────────
  populateSelects();
  render2DMap();
  activeRoute = ROUTE_DB['GF01-FF02'];
  if(activeRoute) {
    const stepsHtml = activeRoute.steps.map((s,i) =>
      `<div class="step-chip"><div class="step-num">${i+1}</div><span>${s}</span></div>${i<activeRoute.steps.length-1?'<span class="step-arrow">›</span>':''}`
    ).join('');
    document.getElementById('stepListContainer').innerHTML = stepsHtml;
    document.getElementById('routeMetrics').innerHTML = `
      <div class="metric-item">⏱ <span class="metric-val">${activeRoute.time}</span></div>
      <div class="metric-item">📏 <span class="metric-val">${activeRoute.dist}</span></div>`;
    document.getElementById('routePanel').classList.add('show');
  }
  toast('Ready — click "Enable GPS" for live tracking');
</script>
</body>
</html>