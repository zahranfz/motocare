<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="MotoCare Digital - Motorcycle Maintenance Tracker & Smart Assistant. Pelacak servis motor berkala, rekomendasi suku cadang, pencari bengkel resmi dan umum, serta buku servis digital PDF."/>
  <title>MotoCare Digital — Smart Maintenance Tracker & Telemetry</title>

  <!-- Typography: Space Grotesk & Hanken Grotesk -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

  <!-- Leaflet CSS for Map (FR-042) -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

  <!-- Tailwind CSS CDN with Stitch Red & Beige Design Tokens -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "surface": "#fef8f3",
            "surface-dim": "#ded9d4",
            "surface-bright": "#fef8f3",
            "surface-container-lowest": "#ffffff",
            "surface-container-low": "#f8f3ee",
            "surface-container": "#f2ede8",
            "surface-container-high": "#ece7e2",
            "surface-container-highest": "#e6e2dd",
            "on-surface": "#1d1b19",
            "on-surface-variant": "#5b403d",
            "inverse-surface": "#32302d",
            "inverse-on-surface": "#f5f0eb",
            "outline": "#8f6f6c",
            "outline-variant": "#e4beba",
            "surface-tint": "#ba1a20",
            "primary": "#af101a",
            "on-primary": "#ffffff",
            "primary-container": "#d32f2f",
            "on-primary-container": "#fff2f0",
            "secondary": "#016d38",
            "on-secondary": "#ffffff",
            "secondary-container": "#9bf6b2",
            "on-secondary-container": "#10733d",
            "tertiary": "#864700",
            "on-tertiary": "#ffffff",
            "tertiary-container": "#aa5b00",
            "on-tertiary-container": "#fff2ea",
            "error": "#ba1a1a",
            "on-error": "#ffffff",
            "error-container": "#ffdad6",
            "on-error-container": "#93000a",
            "primary-fixed": "#ffdad6",
            "on-primary-fixed": "#410003",
            "secondary-fixed": "#9bf6b2",
            "tertiary-fixed": "#ffdcc3",
            "on-tertiary-fixed": "#2f1500"
          },
          borderRadius: {
            DEFAULT: "0.125rem",
            lg: "0.25rem",
            xl: "0.5rem",
            full: "0.75rem"
          },
          spacing: {
            "gutter-lg": "1.75rem",
            "space-lg": "1.5rem",
            "margin": "1rem",
            "space-md": "1rem",
            "gutter-sm": "0.75rem",
            "margin-md": "1.5rem",
            "margin-lg": "2.5rem",
            "space-sm": "0.5rem",
            "gutter": "1.25rem",
            "space-xl": "2.5rem",
            "space-xs": "0.25rem"
          },
          fontFamily: {
            "title-lg": ["Hanken Grotesk", "sans-serif"],
            "telemetry-num": ["Space Grotesk", "monospace"],
            "headline-xl": ["Space Grotesk", "sans-serif"],
            "body-sm": ["Hanken Grotesk", "sans-serif"],
            "headline-md": ["Space Grotesk", "sans-serif"],
            "headline-lg": ["Space Grotesk", "sans-serif"],
            "label-lg": ["Hanken Grotesk", "sans-serif"],
            "body-md": ["Hanken Grotesk", "sans-serif"],
            "label-md": ["Hanken Grotesk", "sans-serif"],
            "label-sm": ["Hanken Grotesk", "sans-serif"],
            "body-lg": ["Hanken Grotesk", "sans-serif"],
            "title-md": ["Hanken Grotesk", "sans-serif"]
          },
          fontSize: {
            "title-lg": ["18px", { "lineHeight": "24px", "fontWeight": "600" }],
            "telemetry-num": ["28px", { "lineHeight": "32px", "letterSpacing": "-0.03em", "fontWeight": "700" }],
            "headline-xl": ["40px", { "lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
            "body-sm": ["12px", { "lineHeight": "16px", "fontWeight": "400" }],
            "headline-md": ["22px", { "lineHeight": "28px", "fontWeight": "600" }],
            "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.015em", "fontWeight": "600" }],
            "label-lg": ["14px", { "lineHeight": "18px", "letterSpacing": "0.02em", "fontWeight": "600" }],
            "headline-xl-mobile": ["30px", { "lineHeight": "38px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
            "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
            "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
            "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.04em", "fontWeight": "600" }],
            "label-sm": ["11px", { "lineHeight": "14px", "letterSpacing": "0.06em", "fontWeight": "600" }],
            "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
            "title-md": ["16px", { "lineHeight": "22px", "fontWeight": "600" }]
          }
        }
      }
    };
  </script>

  <!-- Custom CSS for map markers, print styles, and micro-animations -->
  <link rel="stylesheet" href="assets/css/custom.css"/>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <!-- html2pdf.js for client-side PDF Generation (FR-050) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body class="bg-surface font-body-md text-on-surface min-h-screen flex flex-col selection:bg-primary/20 selection:text-primary">

  <!-- ======================================================== -->
  <!-- TOP HEADER & TELEMETRY NAV (Stitch Red & Beige Architecture) -->
  <!-- ======================================================== -->
  <header class="fixed top-0 w-full z-40 bg-surface/90 backdrop-blur-md shadow-[0_1px_8px_rgba(0,0,0,0.04)] border-b border-surface-container-high/60">
    <div class="h-20 max-w-[1320px] mx-auto px-margin md:px-margin-md lg:px-margin-lg flex items-center justify-between gap-space-md">
      
      <!-- Brand & Active Motor Pill -->
      <div class="flex items-center gap-space-lg">
        <a href="#dashboard" class="flex items-center gap-space-sm group">
          <div class="w-10 h-10 rounded-xl bg-primary text-on-primary flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
            <span class="material-symbols-outlined text-2xl">two_wheeler</span>
          </div>
          <div class="flex flex-col">
            <span class="font-headline-md text-headline-md tracking-tight text-on-surface leading-none">MotoCare</span>
            <span class="font-label-sm text-[11px] text-primary uppercase tracking-widest font-bold">Digital Telemetry</span>
          </div>
        </a>

        <div class="h-8 w-px bg-surface-container-high hidden lg:block"></div>

        <!-- Active Bike Selector Pill -->
        <div id="header-active-bike" onclick="App.switchTab('daftar-motor')" title="Klik untuk mendaftarkan atau melihat motor" class="hidden sm:flex items-center gap-space-sm bg-surface-container-lowest px-space-md py-space-xs rounded-xl shadow-[0_1px_3px_rgba(30,37,43,0.05)] border border-[#e4beba]/40 cursor-pointer hover:bg-surface-container-high transition-all">
          <span class="material-symbols-outlined text-primary text-title-lg">two_wheeler</span>
          <div class="flex flex-col text-left">
            <span class="font-title-md text-title-md leading-tight text-on-surface">Honda Vario 160</span>
            <span class="font-label-sm text-label-sm text-on-surface-variant font-mono">B 4821 KRO</span>
          </div>
          <span class="material-symbols-outlined text-on-surface-variant text-label-lg">unfold_more</span>
        </div>
      </div>

      <!-- Main Navigation Menu -->
      <nav class="hidden xl:flex items-center gap-space-xs bg-surface-container-low p-space-xs rounded-xl border border-surface-container-high/60">
        <a href="#dashboard" data-nav-tab="dashboard" class="px-space-md py-space-sm transition-colors bg-primary-container text-on-primary-container font-label-lg rounded-lg">
          Dashboard Servis
        </a>
        <a href="#peta-bengkel" data-nav-tab="peta-bengkel" class="px-space-md py-space-sm transition-colors text-on-surface-variant font-label-lg hover:text-on-surface">
          Cari Bengkel Terdekat
        </a>
        <a href="#riwayat-servis" data-nav-tab="riwayat-servis" class="px-space-md py-space-sm transition-colors text-on-surface-variant font-label-lg hover:text-on-surface">
          Riwayat Servis
        </a>
        <a href="#buku-servis" data-nav-tab="buku-servis" class="px-space-md py-space-sm transition-colors text-on-surface-variant font-label-lg hover:text-on-surface">
          Buku Servis PDF
        </a>
        <a href="#admin" id="nav-item-admin" data-nav-tab="admin" class="hidden px-space-md py-space-sm transition-colors text-on-surface-variant font-label-lg hover:text-on-surface">
          Data Master
        </a>
      </nav>

      <!-- Quick Odometer Live Widget & User Account Switcher -->
      <div class="flex items-center gap-space-sm sm:gap-space-md">
        
        <!-- Live Odometer Button (FR-020, BR-014) -->
        <button onclick="App.openOdoModal()" class="flex items-center gap-space-xs bg-surface-container-lowest hover:bg-surface-container-high text-on-surface px-space-sm sm:px-space-md py-space-sm rounded-lg shadow-[0_1px_3px_rgba(30,37,43,0.05)] border border-[#e4beba]/40 transition-all" title="Perbarui Odometer Motor" type="button">
          <span class="material-symbols-outlined text-primary text-title-lg">Odometer</span>
          <span class="font-label-md text-label-md font-mono font-bold" id="header-active-odometer">11.200 km</span>
          <span class="material-symbols-outlined text-on-surface-variant text-label-sm">edit</span>
        </button>

        <!-- User Profile Chip (With One-Click Role/Account Switcher) -->
        <div onclick="App.openUserSwitcher()" class="flex items-center gap-space-sm bg-surface-container-lowest p-space-xs pr-space-md rounded-full shadow-[0_1px_3px_rgba(30,37,43,0.05)] border border-[#e4beba]/40 cursor-pointer hover:bg-surface-container-high transition-all" title="Ganti Akun Demo / Hak Akses">
          <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
            <span class="material-symbols-outlined text-lg">person</span>
          </div>
          <div class="hidden md:flex flex-col text-left">
            <span class="font-label-md text-label-md text-on-surface leading-tight user-display-name">Dimas P.</span>
            <span class="user-role-badge font-label-sm text-label-sm text-secondary bg-secondary-container/30 px-space-xs rounded font-semibold">Commuter Pro</span>
          </div>
          <span class="material-symbols-outlined text-on-surface-variant text-sm">swap_horiz</span>
        </div>

      </div>
    </div>
  </header>

  <!-- Mobile Bottom Navigation Bar -->
  <nav class="xl:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface/95 backdrop-blur-md border-t border-surface-container-high shadow-lg px-2 py-2 flex justify-around items-center">
    <a href="#dashboard" class="flex flex-col items-center gap-1 text-on-surface-variant hover:text-primary p-1">
      <span class="material-symbols-outlined text-2xl">speed</span>
      <span class="text-[10px] font-bold">Dashboard</span>
    </a>
    <a href="#peta-bengkel" class="flex flex-col items-center gap-1 text-on-surface-variant hover:text-primary p-1">
      <span class="material-symbols-outlined text-2xl">near_me</span>
      <span class="text-[10px] font-bold">Bengkel</span>
    </a>
    <a href="#riwayat-servis" class="flex flex-col items-center gap-1 text-on-surface-variant hover:text-primary p-1">
      <span class="material-symbols-outlined text-2xl">history</span>
      <span class="text-[10px] font-bold">Riwayat</span>
    </a>
    <a href="#buku-servis" class="flex flex-col items-center gap-1 text-on-surface-variant hover:text-primary p-1">
      <span class="material-symbols-outlined text-2xl">picture_as_pdf</span>
      <span class="text-[10px] font-bold">Buku PDF</span>
    </a>
    <a href="#daftar-motor" class="flex flex-col items-center gap-1 text-on-surface-variant hover:text-primary p-1">
      <span class="material-symbols-outlined text-2xl">Motor</span>
      <span class="text-[10px] font-bold">Motor</span>
    </a>
  </nav>

  <!-- ======================================================== -->
  <!-- MAIN ROUTE CONTAINER -->
  <!-- ======================================================== -->
  <main class="w-full pt-20 pb-20 xl:pb-12 bg-surface min-h-[calc(100vh-160px)] flex-1">
    
    <!-- ====================================================== -->
    <!-- VIEW 1: DASHBOARD SERVIS (UC-03, UC-04, FR-020 - FR-025) -->
    <!-- ====================================================== -->
    <section id="view-dashboard" class="tab-content active w-full">
      <div class="flex flex-col w-full pb-space-xl">
        
        <!-- Top Telemetry & Vehicle Hero Banner (FR-011, FR-020, FR-023) -->
        <div class="max-w-[1320px] mx-auto w-full px-margin md:px-margin-md lg:px-margin-lg pt-space-lg" id="dashboard-hero-banner">
          <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-[#e4beba]/60 relative overflow-hidden flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-6">
            <div class="absolute -right-12 -top-12 opacity-5 pointer-events-none select-none text-on-surface">
              <span class="material-symbols-outlined text-[240px]">settings_suggest</span>
            </div>
            <!-- Vehicle Identity Section -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 z-10">
              <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shrink-0 shadow-sm border border-primary/15">
                <span class="material-symbols-outlined text-[36px]">two_wheeler</span>
              </div>
              <div class="flex flex-col">
                <div class="flex flex-wrap items-center gap-2.5 mb-1.5">
                  <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight font-bold">Honda Vario 160 CBS (2023)</h1>
                  <span class="bg-surface-container text-on-surface font-mono font-semibold px-2.5 py-0.5 rounded text-label-sm tracking-wider border border-surface-container-high">B 4821 KRO</span>
                  <span class="bg-secondary-container/40 text-on-secondary-container px-2 py-0.5 rounded font-label-sm text-label-sm font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">verified</span> Terverifikasi
                  </span>
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-on-surface-variant font-body-sm text-body-sm">
                  <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">event_repeat</span> Pendaftaran: 14 Des 2024
                  </span>
                  <span class="w-1 h-1 rounded-full bg-surface-container-highest"></span>
                  <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">history_toggle_off</span> Terakhir diperbarui: 2 hari lalu
                  </span>
                  <span class="w-1 h-1 rounded-full bg-surface-container-highest"></span>
                  <span class="flex items-center gap-1 text-primary font-semibold">
                    <span class="material-symbols-outlined text-sm">notifications_active</span> 1 Komponen Mendesak
                  </span>
                </div>
              </div>
            </div>
            <!-- Live Odometer Counter Card -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 z-10 bg-surface-container-low p-4 md:p-5 rounded-xl border border-surface-container-high/60">
              <div class="flex flex-col">
                <div class="flex items-center justify-between gap-2 mb-1">
                  <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Odometer Aktif</span>
                  <span class="bg-surface-container-highest text-on-surface-variant text-[10px] font-semibold px-1.5 py-0.5 rounded font-mono">LIVE WIB</span>
                </div>
                <div class="flex items-baseline gap-1.5">
                  <span class="font-telemetry-num text-telemetry-num font-mono text-on-surface tracking-tight" id="heroOdometerVal">11.200</span>
                  <span class="font-title-md text-title-md text-on-surface-variant font-mono font-bold">km</span>
                </div>
              </div>
              <button onclick="App.openOdoModal()" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-primary hover:bg-primary-container text-on-primary px-5 py-3 rounded-xl shadow-sm transition-all active:translate-y-0.5 font-label-lg text-label-lg font-semibold" type="button">
                <span class="material-symbols-outlined text-lg">speed</span>
                <span>Update Odometer</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Status Triage KPI Banner (BR-003, BR-004, BR-005, FR-023) -->
        <div class="max-w-[1320px] mx-auto w-full px-margin md:px-margin-md lg:px-margin-lg pt-space-lg">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">
            
            <!-- Merah / Kritis -->
            <div onclick="App.filterComponentCards('merah')" data-kpi-filter="merah" class="kpi-card bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-center justify-between relative overflow-hidden" title="Klik untuk memfilter komponen Merah">
              <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>
              <div class="flex items-center gap-space-md pl-2">
                <div class="w-12 h-12 rounded-xl bg-error-container/40 text-error flex items-center justify-center">
                  <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">error</span>
                </div>
                <div>
                  <div class="font-headline-md text-headline-md font-mono text-on-surface leading-none" id="kpi-merah-count">1</div>
                  <div class="font-label-md text-label-md text-error font-semibold mt-1">Waktunya Ganti (Merah)</div>
                  <div class="font-body-sm text-body-sm text-on-surface-variant">Sisa km ≤ 0 atau sisa hari ≤ 0</div>
                </div>
              </div>
              <span class="material-symbols-outlined text-error/20 text-4xl">warning</span>
            </div>

            <!-- Kuning / Perhatian -->
            <div onclick="App.filterComponentCards('kuning')" data-kpi-filter="kuning" class="kpi-card bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-center justify-between relative overflow-hidden" title="Klik untuk memfilter komponen Kuning">
              <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-tertiary"></div>
              <div class="flex items-center gap-space-md pl-2">
                <div class="w-12 h-12 rounded-xl bg-tertiary-fixed/40 text-tertiary flex items-center justify-center">
                  <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">report_problem</span>
                </div>
                <div>
                  <div class="font-headline-md text-headline-md font-mono text-on-surface leading-none" id="kpi-kuning-count">1</div>
                  <div class="font-label-md text-label-md text-tertiary font-semibold mt-1">Perlu Perhatian (Kuning)</div>
                  <div class="font-body-sm text-body-sm text-on-surface-variant">&lt; 500 km atau &lt; 7 hari tersisa</div>
                </div>
              </div>
              <span class="material-symbols-outlined text-tertiary/20 text-4xl">schedule</span>
            </div>

            <!-- Hijau / Aman -->
            <div onclick="App.filterComponentCards('hijau')" data-kpi-filter="hijau" class="kpi-card bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-center justify-between relative overflow-hidden" title="Klik untuk memfilter komponen Hijau">
              <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-secondary"></div>
              <div class="flex items-center gap-space-md pl-2">
                <div class="w-12 h-12 rounded-xl bg-secondary-container/50 text-secondary flex items-center justify-center">
                  <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                </div>
                <div>
                  <div class="font-headline-md text-headline-md font-mono text-on-surface leading-none" id="kpi-hijau-count">4</div>
                  <div class="font-label-md text-label-md text-secondary font-semibold mt-1">Kondisi Baik (Hijau)</div>
                  <div class="font-body-sm text-body-sm text-on-surface-variant">Jarak tempuh &amp; waktu aman</div>
                </div>
              </div>
              <span class="material-symbols-outlined text-secondary/20 text-4xl">task_alt</span>
            </div>

          </div>
        </div>

        <!-- Section Title & Interactive Filter Toolbar -->
        <div class="max-w-[1320px] mx-auto w-full px-margin md:px-margin-md lg:px-margin-lg pt-space-lg">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-space-md pb-space-xs">
            <div>
              <h2 class="font-headline-md text-headline-md text-on-surface tracking-tight">Status Komponen Berkala</h2>
              <p class="font-body-sm text-body-sm text-on-surface-variant">
                Urutan prioritas otomatis: <strong>Merah → Kuning → Hijau</strong> (Sesuai ketentuan FR-023 &amp; BR-001 – BR-007)
              </p>
            </div>
            <div class="flex items-center gap-space-sm flex-wrap">
              <a href="#buku-servis" class="flex items-center gap-space-xs bg-surface-container-lowest hover:bg-surface-container-high text-on-surface px-space-md py-2 rounded-lg shadow-sm font-label-md text-label-md transition-all border border-[#e4beba]/40">
                <span class="material-symbols-outlined text-primary text-base">picture_as_pdf</span>
                <span>Ekspor Buku Servis PDF</span>
              </a>
              <button onclick="App.openOdoModal()" class="flex items-center gap-space-xs bg-primary hover:bg-primary-container text-on-primary px-space-md py-2 rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all">
                <span class="material-symbols-outlined text-base">speed</span>
                <span>Update Odometer</span>
              </button>
            </div>
          </div>

          <!-- Filter Pill Tabs Bar -->
          <div class="flex items-center gap-2 pt-3 overflow-x-auto pb-1">
            <button onclick="App.filterComponentCards('all')" data-filter="all" class="filter-pill active px-4 py-1.5 rounded-full flex items-center gap-1.5 cursor-pointer">
              <span>Semua Komponen</span>
              <span class="px-1.5 py-0.2 rounded-full bg-white/20 text-xs font-mono" id="filter-count-all">6</span>
            </button>
            <button onclick="App.filterComponentCards('merah')" data-filter="merah" class="filter-pill px-4 py-1.5 rounded-full flex items-center gap-1.5 cursor-pointer">
              <span class="w-2 h-2 rounded-full bg-primary inline-block"></span>
              <span>Waktunya Ganti</span>
              <span class="px-1.5 py-0.2 rounded-full bg-error-container text-on-error-container text-xs font-mono font-bold" id="filter-count-merah">1</span>
            </button>
            <button onclick="App.filterComponentCards('kuning')" data-filter="kuning" class="filter-pill px-4 py-1.5 rounded-full flex items-center gap-1.5 cursor-pointer">
              <span class="w-2 h-2 rounded-full bg-tertiary inline-block"></span>
              <span>Perlu Perhatian</span>
              <span class="px-1.5 py-0.2 rounded-full bg-tertiary-fixed text-on-tertiary-fixed text-xs font-mono font-bold" id="filter-count-kuning">1</span>
            </button>
            <button onclick="App.filterComponentCards('hijau')" data-filter="hijau" class="filter-pill px-4 py-1.5 rounded-full flex items-center gap-1.5 cursor-pointer">
              <span class="w-2 h-2 rounded-full bg-secondary inline-block"></span>
              <span>Kondisi Baik</span>
              <span class="px-1.5 py-0.2 rounded-full bg-secondary-container/60 text-on-secondary-container text-xs font-mono font-bold" id="filter-count-hijau">4</span>
            </button>
          </div>
        </div>

        <!-- Component Cards Grid (FR-022, FR-023, FR-024, FR-025) -->
        <div class="max-w-[1320px] mx-auto w-full px-margin md:px-margin-md lg:px-margin-lg pt-space-md">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="components-grid">
            
            <!-- Card 1: Oli Mesin (Merah) -->
            <div class="component-card-tactile p-5 md:p-6 relative overflow-hidden flex flex-col justify-between gap-5 transition-all hover:shadow-lg bg-gradient-to-r from-primary/[0.03] to-transparent">
              <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#af101a]"></div>
              <div class="space-y-4 pl-1">
                <div class="flex items-start justify-between gap-2">
                  <div class="space-y-1 min-h-[46px] flex flex-col justify-center">
                    <h3 class="font-title-lg text-title-lg text-on-surface font-bold leading-snug line-clamp-2">Oli Mesin (MPX2 10W-30 0.8L)</h3>
                    <div class="flex items-center gap-1.5">
                      <span class="font-mono text-label-sm text-on-surface-variant font-semibold">Part: 08232-2MB-K0LN</span>
                      <button onclick="navigator.clipboard.writeText('08232-2MB-K0LN'); App.showToast('Kode part disalin!', 'content_copy');" title="Salin Kode Part" class="text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                      </button>
                    </div>
                  </div>
                  <span class="shrink-0 px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold flex items-center gap-1 bg-error-container text-on-error-container">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">error</span>
                    <span>Waktunya Ganti</span>
                  </span>
                </div>
                <div class="space-y-1.5">
                  <div class="flex justify-between items-center text-body-sm font-body-sm text-on-surface-variant">
                    <span>Tingkat Keausan</span>
                    <span class="font-mono font-bold text-primary">100%</span>
                  </div>
                  <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 bg-primary" style="width: 100%;"></div>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-3 bg-surface-container-low/90 p-3.5 rounded-xl border border-surface-container-high/50 font-mono text-body-sm text-on-surface">
                  <div class="flex flex-col">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Jarak Tempuh</span>
                    <span class="font-bold text-[13px] text-error flex items-center min-h-[22px]">Batas 0 km tercapai</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 2.000 km</span>
                  </div>
                  <div class="flex flex-col border-l border-surface-container-high pl-3">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Batas Waktu</span>
                    <span class="font-semibold text-[13px] text-error flex items-center min-h-[22px]">Terlambat 1 hari</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 2 bln</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2 pt-2 border-t border-surface-container-high/40 pl-1">
                <button onclick="App.openResetModal(1)" class="flex-1 h-10 flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-container text-on-primary rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all active:translate-y-0.5" type="button">
                  <span class="material-symbols-outlined text-base">check</span>
                  <span>Sudah Diganti</span>
                </button>
                <button onclick="App.switchTab('peta-bengkel')" title="Cari bengkel resmi terdekat untuk ganti Oli Mesin" class="h-10 px-3.5 flex items-center justify-center gap-1.5 bg-surface-container-high hover:bg-surface-container-highest text-primary border border-primary/20 rounded-lg font-label-md text-label-md font-semibold transition-colors" type="button">
                  <span class="material-symbols-outlined text-base">near_me</span>
                  <span class="hidden sm:inline">Bengkel</span>
                </button>
              </div>
            </div>

            <!-- Card 2: Filter Udara (Kuning) -->
            <div class="component-card-tactile p-5 md:p-6 relative overflow-hidden flex flex-col justify-between gap-5 transition-all hover:shadow-lg bg-gradient-to-r from-tertiary/[0.03] to-transparent">
              <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#864700]"></div>
              <div class="space-y-4 pl-1">
                <div class="flex items-start justify-between gap-2">
                  <div class="space-y-1 min-h-[46px] flex flex-col justify-center">
                    <h3 class="font-title-lg text-title-lg text-on-surface font-bold leading-snug line-clamp-2">Filter Udara (Air Cleaner)</h3>
                    <div class="flex items-center gap-1.5">
                      <span class="font-mono text-label-sm text-on-surface-variant font-semibold">Part: 17210-K0S-V00</span>
                      <button onclick="navigator.clipboard.writeText('17210-K0S-V00'); App.showToast('Kode part disalin!', 'content_copy');" title="Salin Kode Part" class="text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                      </button>
                    </div>
                  </div>
                  <span class="shrink-0 px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold flex items-center gap-1 bg-tertiary-fixed text-on-tertiary-fixed">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">report_problem</span>
                    <span>Perlu Perhatian</span>
                  </span>
                </div>
                <div class="space-y-1.5">
                  <div class="flex justify-between items-center text-body-sm font-body-sm text-on-surface-variant">
                    <span>Tingkat Keausan</span>
                    <span class="font-mono font-bold text-tertiary">96%</span>
                  </div>
                  <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 bg-tertiary" style="width: 96%;"></div>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-3 bg-surface-container-low/90 p-3.5 rounded-xl border border-surface-container-high/50 font-mono text-body-sm text-on-surface">
                  <div class="flex flex-col">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Jarak Tempuh</span>
                    <span class="font-bold text-[13px] text-tertiary flex items-center min-h-[22px]">300 km tersisa</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 8.000 km</span>
                  </div>
                  <div class="flex flex-col border-l border-surface-container-high pl-3">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Batas Waktu</span>
                    <span class="font-semibold text-[13px] text-on-surface flex items-center min-h-[22px]">60 hari tersisa</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 8 bln</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2 pt-2 border-t border-surface-container-high/40 pl-1">
                <button onclick="App.openResetModal(2)" class="flex-1 h-10 flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-container text-on-primary rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all active:translate-y-0.5" type="button">
                  <span class="material-symbols-outlined text-base">check</span>
                  <span>Sudah Diganti</span>
                </button>
                <button onclick="App.switchTab('peta-bengkel')" title="Cari bengkel resmi terdekat untuk ganti Filter Udara" class="h-10 px-3.5 flex items-center justify-center gap-1.5 bg-surface-container-high hover:bg-surface-container-highest text-primary border border-primary/20 rounded-lg font-label-md text-label-md font-semibold transition-colors" type="button">
                  <span class="material-symbols-outlined text-base">near_me</span>
                  <span class="hidden sm:inline">Bengkel</span>
                </button>
              </div>
            </div>

            <!-- Card 3: Busi (Hijau) -->
            <div class="component-card-tactile p-5 md:p-6 relative overflow-hidden flex flex-col justify-between gap-5 transition-all hover:shadow-lg">
              <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#016d38]"></div>
              <div class="space-y-4 pl-1">
                <div class="flex items-start justify-between gap-2">
                  <div class="space-y-1 min-h-[46px] flex flex-col justify-center">
                    <h3 class="font-title-lg text-title-lg text-on-surface font-bold leading-snug line-clamp-2">Busi (Spark Plug NGK CPR9EA-9)</h3>
                    <div class="flex items-center gap-1.5">
                      <span class="font-mono text-label-sm text-on-surface-variant font-semibold">Part: 31916-K97-T01</span>
                      <button onclick="navigator.clipboard.writeText('31916-K97-T01'); App.showToast('Kode part disalin!', 'content_copy');" title="Salin Kode Part" class="text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                      </button>
                    </div>
                  </div>
                  <span class="shrink-0 px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold flex items-center gap-1 bg-secondary-container/40 text-on-secondary-container">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>Kondisi Baik</span>
                  </span>
                </div>
                <div class="space-y-1.5">
                  <div class="flex justify-between items-center text-body-sm font-body-sm text-on-surface-variant">
                    <span>Tingkat Keausan</span>
                    <span class="font-mono font-bold text-on-surface">40%</span>
                  </div>
                  <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 bg-secondary" style="width: 40%;"></div>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-3 bg-surface-container-low/90 p-3.5 rounded-xl border border-surface-container-high/50 font-mono text-body-sm text-on-surface">
                  <div class="flex flex-col">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Jarak Tempuh</span>
                    <span class="font-semibold text-[13px] text-on-surface flex items-center min-h-[22px]">4.800 km tersisa</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 8.000 km</span>
                  </div>
                  <div class="flex flex-col border-l border-surface-container-high pl-3">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Batas Waktu</span>
                    <span class="font-semibold text-[13px] text-on-surface flex items-center min-h-[22px]">150 hari tersisa</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 8 bln</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2 pt-2 border-t border-surface-container-high/40 pl-1">
                <button onclick="App.openResetModal(3)" class="flex-1 h-10 flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-container text-on-primary rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all active:translate-y-0.5" type="button">
                  <span class="material-symbols-outlined text-base">check</span>
                  <span>Sudah Diganti</span>
                </button>
                <button onclick="App.switchTab('peta-bengkel')" title="Cari bengkel terdekat" class="h-10 px-3.5 flex items-center justify-center gap-1.5 bg-surface-container-low hover:bg-surface-container-high text-on-surface-variant rounded-lg font-label-md text-label-md transition-colors" type="button">
                  <span class="material-symbols-outlined text-primary text-base">near_me</span>
                  <span class="hidden sm:inline">Bengkel</span>
                </button>
              </div>
            </div>

            <!-- Card 4: Kampas Rem Depan (Hijau) -->
            <div class="component-card-tactile p-5 md:p-6 relative overflow-hidden flex flex-col justify-between gap-5 transition-all hover:shadow-lg">
              <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#016d38]"></div>
              <div class="space-y-4 pl-1">
                <div class="flex items-start justify-between gap-2">
                  <div class="space-y-1 min-h-[46px] flex flex-col justify-center">
                    <h3 class="font-title-lg text-title-lg text-on-surface font-bold leading-snug line-clamp-2">Kampas Rem Depan (Pad Set Fr)</h3>
                    <div class="flex items-center gap-1.5">
                      <span class="font-mono text-label-sm text-on-surface-variant font-semibold">Part: 06455-KRE-K01</span>
                      <button onclick="navigator.clipboard.writeText('06455-KRE-K01'); App.showToast('Kode part disalin!', 'content_copy');" title="Salin Kode Part" class="text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                      </button>
                    </div>
                  </div>
                  <span class="shrink-0 px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold flex items-center gap-1 bg-secondary-container/40 text-on-secondary-container">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>Kondisi Baik</span>
                  </span>
                </div>
                <div class="space-y-1.5">
                  <div class="flex justify-between items-center text-body-sm font-body-sm text-on-surface-variant">
                    <span>Tingkat Keausan</span>
                    <span class="font-mono font-bold text-on-surface">52%</span>
                  </div>
                  <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 bg-secondary" style="width: 52%;"></div>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-3 bg-surface-container-low/90 p-3.5 rounded-xl border border-surface-container-high/50 font-mono text-body-sm text-on-surface">
                  <div class="flex flex-col">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Jarak Tempuh</span>
                    <span class="font-semibold text-[13px] text-on-surface flex items-center min-h-[22px]">4.800 km tersisa</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 10.000 km</span>
                  </div>
                  <div class="flex flex-col border-l border-surface-container-high pl-3">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Batas Waktu</span>
                    <span class="font-semibold text-[13px] text-on-surface flex items-center min-h-[22px]">240 hari tersisa</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 12 bln</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2 pt-2 border-t border-surface-container-high/40 pl-1">
                <button onclick="App.openResetModal(4)" class="flex-1 h-10 flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-container text-on-primary rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all active:translate-y-0.5" type="button">
                  <span class="material-symbols-outlined text-base">check</span>
                  <span>Sudah Diganti</span>
                </button>
                <button onclick="App.switchTab('peta-bengkel')" title="Cari bengkel terdekat" class="h-10 px-3.5 flex items-center justify-center gap-1.5 bg-surface-container-low hover:bg-surface-container-high text-on-surface-variant rounded-lg font-label-md text-label-md transition-colors" type="button">
                  <span class="material-symbols-outlined text-primary text-base">near_me</span>
                  <span class="hidden sm:inline">Bengkel</span>
                </button>
              </div>
            </div>

            <!-- Card 5: Oli Gardan / Transmisi (Hijau) -->
            <div class="component-card-tactile p-5 md:p-6 relative overflow-hidden flex flex-col justify-between gap-5 transition-all hover:shadow-lg">
              <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#016d38]"></div>
              <div class="space-y-4 pl-1">
                <div class="flex items-start justify-between gap-2">
                  <div class="space-y-1 min-h-[46px] flex flex-col justify-center">
                    <h3 class="font-title-lg text-title-lg text-on-surface font-bold leading-snug line-clamp-2">Oli Gardan / Transmisi (120ml)</h3>
                    <div class="flex items-center gap-1.5">
                      <span class="font-mono text-label-sm text-on-surface-variant font-semibold">Part: 08234-2MB-K00</span>
                      <button onclick="navigator.clipboard.writeText('08234-2MB-K00'); App.showToast('Kode part disalin!', 'content_copy');" title="Salin Kode Part" class="text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                      </button>
                    </div>
                  </div>
                  <span class="shrink-0 px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold flex items-center gap-1 bg-secondary-container/40 text-on-secondary-container">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>Kondisi Baik</span>
                  </span>
                </div>
                <div class="space-y-1.5">
                  <div class="flex justify-between items-center text-body-sm font-body-sm text-on-surface-variant">
                    <span>Tingkat Keausan</span>
                    <span class="font-mono font-bold text-on-surface">40%</span>
                  </div>
                  <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 bg-secondary" style="width: 40%;"></div>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-3 bg-surface-container-low/90 p-3.5 rounded-xl border border-surface-container-high/50 font-mono text-body-sm text-on-surface">
                  <div class="flex flex-col">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Jarak Tempuh</span>
                    <span class="font-semibold text-[13px] text-on-surface flex items-center min-h-[22px]">4.800 km tersisa</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 8.000 km</span>
                  </div>
                  <div class="flex flex-col border-l border-surface-container-high pl-3">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Batas Waktu</span>
                    <span class="font-semibold text-[13px] text-on-surface flex items-center min-h-[22px]">150 hari tersisa</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 8 bln</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2 pt-2 border-t border-surface-container-high/40 pl-1">
                <button onclick="App.openResetModal(6)" class="flex-1 h-10 flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-container text-on-primary rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all active:translate-y-0.5" type="button">
                  <span class="material-symbols-outlined text-base">check</span>
                  <span>Sudah Diganti</span>
                </button>
                <button onclick="App.switchTab('peta-bengkel')" title="Cari bengkel terdekat" class="h-10 px-3.5 flex items-center justify-center gap-1.5 bg-surface-container-low hover:bg-surface-container-high text-on-surface-variant rounded-lg font-label-md text-label-md transition-colors" type="button">
                  <span class="material-symbols-outlined text-primary text-base">near_me</span>
                  <span class="hidden sm:inline">Bengkel</span>
                </button>
              </div>
            </div>

            <!-- Card 6: Kampas Rem Belakang (Hijau) -->
            <div class="component-card-tactile p-5 md:p-6 relative overflow-hidden flex flex-col justify-between gap-5 transition-all hover:shadow-lg">
              <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#016d38]"></div>
              <div class="space-y-4 pl-1">
                <div class="flex items-start justify-between gap-2">
                  <div class="space-y-1 min-h-[46px] flex flex-col justify-center">
                    <h3 class="font-title-lg text-title-lg text-on-surface font-bold leading-snug line-clamp-2">Kampas Rem Belakang (Shoe Set Rr)</h3>
                    <div class="flex items-center gap-1.5">
                      <span class="font-mono text-label-sm text-on-surface-variant font-semibold">Part: 43130-KZL-930</span>
                      <button onclick="navigator.clipboard.writeText('43130-KZL-930'); App.showToast('Kode part disalin!', 'content_copy');" title="Salin Kode Part" class="text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                      </button>
                    </div>
                  </div>
                  <span class="shrink-0 px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold flex items-center gap-1 bg-secondary-container/40 text-on-secondary-container">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>Kondisi Baik</span>
                  </span>
                </div>
                <div class="space-y-1.5">
                  <div class="flex justify-between items-center text-body-sm font-body-sm text-on-surface-variant">
                    <span>Tingkat Keausan</span>
                    <span class="font-mono font-bold text-on-surface">42%</span>
                  </div>
                  <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 bg-secondary" style="width: 42%;"></div>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-3 bg-surface-container-low/90 p-3.5 rounded-xl border border-surface-container-high/50 font-mono text-body-sm text-on-surface">
                  <div class="flex flex-col">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Jarak Tempuh</span>
                    <span class="font-semibold text-[13px] text-on-surface flex items-center min-h-[22px]">5.800 km tersisa</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 10.000 km</span>
                  </div>
                  <div class="flex flex-col border-l border-surface-container-high pl-3">
                    <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Batas Waktu</span>
                    <span class="font-semibold text-[13px] text-on-surface flex items-center min-h-[22px]">260 hari tersisa</span>
                    <span class="text-[10px] text-on-surface-variant">Interval: 12 bln</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2 pt-2 border-t border-surface-container-high/40 pl-1">
                <button onclick="App.openResetModal(5)" class="flex-1 h-10 flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-container text-on-primary rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all active:translate-y-0.5" type="button">
                  <span class="material-symbols-outlined text-base">check</span>
                  <span>Sudah Diganti</span>
                </button>
                <button onclick="App.switchTab('peta-bengkel')" title="Cari bengkel terdekat" class="h-10 px-3.5 flex items-center justify-center gap-1.5 bg-surface-container-low hover:bg-surface-container-high text-on-surface-variant rounded-lg font-label-md text-label-md transition-colors" type="button">
                  <span class="material-symbols-outlined text-primary text-base">near_me</span>
                  <span class="hidden sm:inline">Bengkel</span>
                </button>
              </div>
            </div>

          </div>
        </div>

      </div>
    </section>

    <!-- ====================================================== -->
    <!-- VIEW 2: CARI BENGKEL TERDEKAT (UC-05, FR-040 - FR-045) -->
    <!-- ====================================================== -->
    <section id="view-peta-bengkel" class="tab-content w-full">
      <div class="max-w-[1320px] mx-auto px-margin md:px-margin-md lg:px-margin-lg py-space-md">
        
        <!-- Filter Header Area -->
        <div class="flex flex-col gap-space-sm mb-space-lg">
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md bg-surface-container-low p-space-md md:p-space-lg rounded-xl shadow-sm border border-surface-container-high">
            <div class="space-y-space-xs">
              <div class="flex items-center gap-space-xs">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary-container text-on-primary-container text-label-sm font-bold">FR</span>
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-primary font-bold">Smart Nearest Workshop Finder</span>
              </div>
              <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Cari Bengkel Motor Terdekat</h1>
              <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
                Menemukan bengkel resmi AHASS/Yamaha dan bengkel umum terverifikasi di sekitar lokasi Anda (Peta Leaflet &amp; OpenStreetMap, BR-012, BR-013).
              </p>
            </div>

            <!-- GPS Status Chip & Radius Selector -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-space-sm bg-surface-container-lowest p-space-xs rounded-xl shadow-sm border border-[#e4beba]/40">
              <div class="flex items-center gap-space-xs px-space-sm py-space-xs text-on-surface">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                </span>
                <div class="flex flex-col">
                  <span class="font-label-xs text-[10px] uppercase tracking-wider text-on-surface-variant font-semibold">Titik Acuan GPS</span>
                  <span class="font-label-md text-label-md font-bold text-on-surface flex items-center gap-1" id="gps-acuan-label">
                    Jl. Margonda Raya, Depok
                  </span>
                </div>
              </div>
              <div class="h-6 w-px bg-surface-container-high hidden sm:block"></div>

              <!-- Radius Selector (BR-012) -->
              <div class="relative flex items-center">
                <label class="sr-only" for="radiusSelect">Radius Pencarian</label>
                <div class="flex items-center gap-1 px-space-sm py-1 bg-surface-container rounded-lg">
                  <span class="material-symbols-outlined text-primary text-[18px]">radar</span>
                  <select onchange="App.setWorkshopRadius(this.value)" id="radiusSelect" class="bg-transparent text-on-surface font-label-md text-label-md font-bold py-1 pr-6 cursor-pointer focus:outline-none appearance-none">
                    <option value="3">Radius: 3 km</option>
                    <option value="5" selected>Radius: 5 km (Default)</option>
                    <option value="10">Radius: 10 km (Maks)</option>
                  </select>
                  <span class="material-symbols-outlined text-on-surface-variant text-[16px] pointer-events-none -ml-5">expand_more</span>
                </div>
              </div>

              <!-- Geolocation request button (FR-040, FR-045) -->
              <button onclick="App.requestUserLocation()" title="Gunakan Lokasi GPS Saya (FR-040)" class="p-2 hover:bg-surface-container rounded-lg text-on-surface-variant hover:text-primary transition-colors" type="button">
                <span class="material-symbols-outlined text-[20px]">my_location</span>
              </button>
            </div>
          </div>

          <!-- Category Filter Toggles (BR-013) -->
          <div class="flex flex-wrap items-center justify-between gap-space-sm pt-space-xs">
            <div class="flex flex-wrap items-center gap-space-xs">
              <button onclick="App.setWorkshopFilter('all')" id="filter-btn-all" class="flex items-center gap-2 px-space-md py-space-xs rounded-lg font-label-md text-label-md transition-all bg-on-surface text-inverse-on-surface shadow-sm" type="button">
                <span class="material-symbols-outlined text-[16px]">storefront</span>
                <span>Semua</span>
                <span id="badge-count-all" class="px-1.5 py-0.5 rounded-full bg-surface/20 text-inverse-on-surface text-[11px] font-mono">8</span>
              </button>
              <button onclick="App.setWorkshopFilter('resmi')" id="filter-btn-resmi" class="flex items-center gap-2 px-space-md py-space-xs rounded-lg font-label-md text-label-md transition-all bg-surface-container-lowest text-on-surface hover:bg-surface-container shadow-sm border border-[#e4beba]/30" type="button">
                <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                <span class="font-semibold text-primary">Bengkel Resmi (AHASS / Yamaha)</span>
                <span id="badge-count-resmi" class="px-1.5 py-0.5 rounded-full bg-primary/10 text-primary text-[11px] font-mono font-bold">3</span>
              </button>
              <button onclick="App.setWorkshopFilter('umum')" id="filter-btn-umum" class="flex items-center gap-2 px-space-md py-space-xs rounded-lg font-label-md text-label-md transition-all bg-surface-container-lowest text-on-surface hover:bg-surface-container shadow-sm border border-[#e4beba]/30" type="button">
                <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                <span class="font-semibold text-secondary">Bengkel Umum &amp; Spesialis</span>
                <span id="badge-count-umum" class="px-1.5 py-0.5 rounded-full bg-secondary/10 text-secondary text-[11px] font-mono font-bold">5</span>
              </button>
            </div>
            <div class="flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm">
              <span class="material-symbols-outlined text-[16px] text-secondary">verified_user</span>
              <span>Klik pada peta untuk memilih lokasi pencarian manual (FR-045)</span>
            </div>
          </div>
        </div>

        <!-- Split Screen: Workshop List (Left) & Leaflet Map (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
          
          <!-- Workshop Listing Column (5 cols on lg) -->
          <div class="lg:col-span-5 flex flex-col gap-space-sm order-2 lg:order-1">
            <div class="flex items-center justify-between px-space-xs mb-space-xs">
              <span class="font-label-md text-label-md font-bold uppercase tracking-wider text-on-surface-variant">Daftar Terdekat (Urut Jarak)</span>
              <span id="workshop-result-count" class="font-label-sm text-label-sm text-on-surface-variant">Menampilkan bengkel terdekat...</span>
            </div>
            
            <div id="workshop-list-container" class="flex flex-col gap-space-sm max-h-[640px] overflow-y-auto pr-1">
              <!-- Dynamically populated from App.renderWorkshopList() -->
            </div>
          </div>

          <!-- Interactive Leaflet Map Column (7 cols on lg) -->
          <div class="lg:col-span-7 order-1 lg:order-2 sticky top-24">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-[#e4beba]/40 overflow-hidden relative">
              <div id="leaflet-map" class="w-full h-[580px] z-10"></div>
              <!-- Floating Legend -->
              <div class="absolute bottom-4 left-4 z-20 bg-surface-container-lowest/90 backdrop-blur-md px-3 py-2 rounded-lg shadow-md border border-[#e4beba]/60 flex items-center gap-4 text-xs">
                <div class="flex items-center gap-1.5">
                  <span class="w-3 h-3 rounded-full bg-primary inline-block"></span>
                  <span class="font-semibold text-primary">Bengkel Resmi</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <span class="w-3 h-3 rounded-full bg-secondary inline-block"></span>
                  <span class="font-semibold text-secondary">Bengkel Umum</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span>
                  <span class="font-semibold text-blue-800">Posisi Anda</span>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- ====================================================== -->
    <!-- VIEW 3: RIWAYAT SERVIS (UC-04, FR-034, FR-035, BR-016) -->
    <!-- ====================================================== -->
    <section id="view-riwayat-servis" class="tab-content w-full">
      <div class="max-w-[1320px] mx-auto w-full px-margin md:px-margin-md lg:px-margin-lg py-space-lg flex flex-col gap-space-lg">
        
        <!-- Top Action & Title Area -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-lg">
          <div class="flex flex-col gap-space-xs max-w-2xl">
            <div class="flex items-center gap-space-xs">
              <span class="inline-block w-2.5 h-2.5 rounded-full bg-secondary"></span>
              <span class="font-label-sm text-label-sm uppercase tracking-wider text-secondary font-semibold">Buku Log Telemetri Perawatan</span>
            </div>
            <h1 class="font-headline-lg md:font-headline-xl text-headline-lg md:text-headline-xl text-on-surface tracking-tight">
              Riwayat Servis &amp; Suku Cadang
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant">
              Daftar lengkap log pemeliharaan komponen sepeda motor Anda. Sesuai ketentuan <strong>BR-016 &amp; BR-017</strong>, catatan tersimpan permanen dan pembatalan hanya berlaku dalam 24 jam untuk penggantian terakhir.
            </p>
          </div>
          <div class="flex items-center flex-wrap gap-space-sm">
            <a href="#buku-servis" class="flex items-center gap-space-xs px-space-md py-space-sm bg-surface-container-lowest text-on-surface hover:bg-surface-container-high transition-all rounded-lg shadow-sm font-label-lg text-label-lg border border-[#e4beba]/40">
              <span class="material-symbols-outlined text-primary text-title-lg">picture_as_pdf</span>
              <span>Buku Servis PDF</span>
            </a>
            <button onclick="App.openManualServiceModal()" class="flex items-center gap-space-xs px-space-lg py-space-sm bg-primary text-on-primary hover:bg-primary-container transition-all rounded-lg shadow-md font-label-lg text-label-lg" type="button">
              <span class="material-symbols-outlined text-title-lg">add_circle</span>
              <span>+ Catat Servis Manual</span>
            </button>
          </div>
        </div>

        <!-- Telemetry Summary Metric Strip -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">
          <!-- Metric 1: Total Servis -->
          <div class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-[#e4beba]/30 flex flex-col justify-between gap-space-md relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-secondary"></div>
            <div class="flex items-start justify-between pl-1">
              <div class="flex flex-col">
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant">Total Servis Tercatat</span>
                <span id="hist-total-servis" class="font-telemetry-num text-telemetry-num text-on-surface mt-space-xs">0 Kali</span>
              </div>
              <div class="flex items-center gap-1 bg-secondary-container/40 text-on-secondary-container px-space-xs py-1 rounded-full text-label-sm font-semibold">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <span>Terverifikasi</span>
              </div>
            </div>
            <div class="flex items-center justify-between text-body-sm font-body-sm text-on-surface-variant pt-space-xs pl-1">
              <span>Odo Pertama: <strong id="hist-first-odo" class="font-mono text-on-surface">-</strong></span>
              <span>Terakhir: <strong id="hist-last-odo" class="font-mono text-on-surface">-</strong></span>
            </div>
          </div>

          <!-- Metric 2: Total Biaya Suku Cadang -->
          <div class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-[#e4beba]/30 flex flex-col justify-between gap-space-md relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-tertiary"></div>
            <div class="flex items-start justify-between pl-1">
              <div class="flex flex-col">
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant">Total Biaya Suku Cadang</span>
                <span id="hist-total-biaya" class="font-telemetry-num text-telemetry-num text-on-surface mt-space-xs">Rp 0</span>
              </div>
              <div class="p-space-xs rounded-lg bg-surface-container-high text-tertiary">
                <span class="material-symbols-outlined text-title-lg">payments</span>
              </div>
            </div>
            <div class="flex items-center justify-between text-body-sm font-body-sm text-on-surface-variant pt-space-xs pl-1">
              <span>Transparansi Log</span>
              <span class="text-secondary font-medium">Rekapitulasi Akurat</span>
            </div>
          </div>

          <!-- Metric 3: Kedisiplinan Servis -->
          <div class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-[#e4beba]/30 flex flex-col justify-between gap-space-md relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-secondary"></div>
            <div class="flex items-start justify-between pl-1">
              <div class="flex flex-col">
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant">Integritas Data Servis</span>
                <span class="font-telemetry-num text-telemetry-num text-on-surface mt-space-xs">100% Digital</span>
              </div>
              <div class="flex items-center gap-1 bg-secondary-container/40 text-on-secondary-container px-space-xs py-1 rounded-full text-label-sm font-semibold">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                <span>Standar PRD</span>
              </div>
            </div>
            <div class="flex items-center justify-between text-body-sm font-body-sm text-on-surface-variant pt-space-xs pl-1">
              <span>Buku Servis Digital Terkoneksi</span>
              <span class="font-semibold text-secondary">Anti Rusak / Hilang</span>
            </div>
          </div>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-surface-container-low p-space-md rounded-xl shadow-sm border border-surface-container-high flex flex-col md:flex-row md:items-center justify-between gap-space-md">
          <div class="flex flex-wrap items-center gap-space-sm flex-1">
            <div class="relative flex-1 max-w-xs">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
              <input type="text" id="hist-search-input" placeholder="Cari nama komponen / bengkel..." class="w-full pl-9 pr-3 py-1.5 rounded-lg bg-surface-container-lowest border border-[#e4beba]/60 text-xs text-on-surface focus:outline-none focus:ring-1 focus:ring-primary"/>
            </div>
            <select id="hist-filter-component" class="bg-surface-container-lowest text-on-surface font-label-md text-xs px-3 py-1.5 rounded-lg border border-[#e4beba]/60 shadow-sm cursor-pointer">
              <option value="all">Semua Komponen</option>
              <option value="oli">Oli Mesin</option>
              <option value="busi">Busi</option>
              <option value="filter">Filter Udara</option>
              <option value="rem">Kampas Rem</option>
              <option value="gardan">Oli Gardan</option>
            </select>
          </div>
          <span class="text-xs text-on-surface-variant font-mono">Ketentuan: Batalkan penggantian maksimal 24 jam</span>
        </div>

        <!-- Service History Table Card -->
        <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-[#e4beba]/30 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-surface-container-low border-b border-surface-container-high text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">
                  <th class="p-space-md">Tanggal</th>
                  <th class="p-space-md">Komponen &amp; Suku Cadang</th>
                  <th class="p-space-md">Odometer</th>
                  <th class="p-space-md">Bengkel / Lokasi</th>
                  <th class="p-space-md">Biaya</th>
                  <th class="p-space-md">Status</th>
                  <th class="p-space-md text-right">Aksi</th>
                </tr>
              </thead>
              <tbody id="history-table-body">
                <!-- Dynamically populated from App.renderHistory() -->
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </section>

    <!-- ====================================================== -->
    <!-- VIEW 4: BUKU SERVIS DIGITAL PDF (UC-06, FR-050 - FR-053) -->
    <!-- ====================================================== -->
    <section id="view-buku-servis" class="tab-content w-full">
      
      <!-- Top Action Command Bar -->
      <div class="w-full bg-surface-container-low shadow-sm py-space-md border-b border-surface-container-high">
        <div class="max-w-[1320px] mx-auto px-margin md:px-margin-md lg:px-margin-lg flex flex-col lg:flex-row lg:items-center justify-between gap-space-md">
          <div class="flex flex-col">
            <div class="flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">
              <span class="material-symbols-outlined text-primary text-title-md">verified</span>
              <span>Buku Servis Elektronik • Modul Ekspor Resmi (FR-050 - FR-053)</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight mt-space-xs">
              Buku Servis Digital (Digital Maintenance Logbook)
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl mt-0.5">
              Dokumen riwayat perawatan resmi untuk bukti jual beli &amp; rekam jejak performa motor berkala terverifikasi.
            </p>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-wrap items-center gap-space-sm pt-space-xs lg:pt-0">
            <button onclick="App.downloadPdf()" class="flex items-center gap-space-xs bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg px-space-md py-space-sm rounded-lg shadow-sm transition-all active:translate-y-0.5" type="button">
              <span class="material-symbols-outlined text-title-lg">download</span>
              <span>Unduh PDF (.pdf)</span>
            </button>
            <button onclick="window.print()" class="flex items-center gap-space-xs bg-surface-container-lowest text-on-surface hover:bg-surface-container-high font-label-lg text-label-lg px-space-md py-space-sm rounded-lg shadow-sm border border-[#e4beba]/40 transition-all" type="button">
              <span class="material-symbols-outlined text-title-lg text-on-surface-variant">print</span>
              <span>Cetak Dokumen</span>
            </button>
            <div class="hidden sm:flex items-center pl-space-xs">
              <span id="pdf-doc-id" class="bg-secondary-container/50 text-on-secondary-container font-label-sm text-label-sm px-space-sm py-1 rounded-full flex items-center gap-1 font-mono">
                ID: MC-2026-8812A
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Document Preview Stage -->
      <div class="max-w-[1320px] mx-auto px-margin md:px-margin-md lg:px-margin-lg py-space-lg">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
          
          <!-- Left Sidebar Controls -->
          <div class="lg:col-span-4 flex flex-col gap-space-md order-2 lg:order-1 no-print">
            <div class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-[#e4beba]/40 flex flex-col gap-space-md">
              <div class="flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Status Sertifikasi</span>
                <span class="bg-secondary-container text-on-secondary-container font-label-sm text-label-sm px-2 py-0.5 rounded-full flex items-center gap-1 font-semibold">
                  <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                  Valid &amp; Aktif
                </span>
              </div>
              <div class="h-px bg-surface-container"></div>
              <div class="space-y-2 text-xs">
                <div class="flex justify-between"><span class="text-on-surface-variant">Format Dokumen:</span><span class="font-semibold text-on-surface">Standar PDF A4 Universal</span></div>
                <div class="flex justify-between"><span class="text-on-surface-variant">Zona Waktu:</span><span class="font-semibold text-on-surface">Asia/Jakarta (WIB)</span></div>
                <div class="flex justify-between"><span class="text-on-surface-variant">Kepatuhan Hukum:</span><span class="font-semibold text-primary">FR-053 Keterangan Mandiri</span></div>
              </div>
              <div class="bg-surface-container-low p-space-sm rounded-lg text-body-sm text-on-surface-variant">
                <p class="text-[12px] leading-relaxed">
                  Dokumen ini memenuhi klausul transparansi riwayat kendaraan untuk peningkatan nilai jual kembali (secondhand resale booster).
                </p>
              </div>
            </div>

            <!-- Zoom Preview Controls -->
            <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm border border-[#e4beba]/40 flex items-center justify-between">
              <div class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-xs">
                <span class="material-symbols-outlined text-lg">aspect_ratio</span>
                <span>Skala Tampilan (A4)</span>
              </div>
              <div class="flex items-center gap-1">
                <button onclick="App.zoomPdf(-10)" class="w-7 h-7 rounded bg-surface-container hover:bg-surface-container-high flex items-center justify-center font-bold text-xs">-</button>
                <span id="pdf-zoom-text" class="font-mono text-xs px-2">100%</span>
                <button onclick="App.zoomPdf(10)" class="w-7 h-7 rounded bg-surface-container hover:bg-surface-container-high flex items-center justify-center font-bold text-xs">+</button>
              </div>
            </div>
          </div>

          <!-- Document Sheet (A4 White Canvas) -->
          <div class="lg:col-span-8 order-1 lg:order-2 flex justify-center overflow-x-auto p-2">
            <div id="pdf-document-sheet" class="p-8 sm:p-12 transition-transform origin-top">
              
              <!-- PDF Header -->
              <div class="border-b-2 border-primary pb-6 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                  <div class="w-12 h-12 rounded-xl bg-primary text-white flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">two_wheeler</span>
                  </div>
                  <div>
                    <h2 class="font-headline-md text-2xl font-bold tracking-tight text-gray-900 leading-none">MotoCare Digital</h2>
                    <p class="text-xs text-primary font-bold uppercase tracking-widest mt-1">Buku Servis Elektronik &amp; Log Pemeliharaan Berkala</p>
                  </div>
                </div>
                <div class="text-right text-xs space-y-0.5">
                  <div class="font-mono font-bold text-gray-900 text-sm" id="pdf-doc-id-sheet">MC-2026-8812A</div>
                  <div class="text-gray-500">Tanggal Ekspor: <span id="pdf-doc-date" class="font-medium text-gray-800">17 Okt 2026</span></div>
                  <div class="text-green-700 font-semibold flex items-center justify-end gap-1">
                    <span class="material-symbols-outlined text-xs">verified</span> Dokumen Terverifikasi Digital
                  </div>
                </div>
              </div>

              <!-- Vehicle & Owner Identification Block -->
              <div class="bg-[#f8f3ee] p-4 rounded-lg mb-6 border border-[#e4beba]/60">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#5b403d] mb-3">Identitas Kendaraan &amp; Kepemilikan</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                  <div>
                    <span class="text-gray-500 block">Pemilik Kendaraan:</span>
                    <strong id="pdf-doc-owner" class="text-gray-900 text-sm">Dimas Prasetyo</strong>
                  </div>
                  <div>
                    <span class="text-gray-500 block">Merek &amp; Tipe:</span>
                    <strong id="pdf-doc-bike" class="text-gray-900 text-sm">Honda Vario 160 CBS</strong>
                  </div>
                  <div>
                    <span class="text-gray-500 block">Nomor Polisi:</span>
                    <strong id="pdf-doc-plate" class="text-primary font-mono text-sm">B 4821 KRO</strong>
                  </div>
                  <div>
                    <span class="text-gray-500 block">Odometer Saat Ekspor:</span>
                    <strong id="pdf-doc-odo" class="text-gray-900 font-mono text-sm">11.200 km</strong>
                  </div>
                </div>
              </div>

              <!-- Service Records Chronological Table (FR-051) -->
              <div class="mb-8">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800 mb-3 flex items-center justify-between">
                  <span>Catatan Penggantian Komponen (Kronologis)</span>
                  <span class="text-gray-500 font-normal">Diurutkan dari catatan terbaru</span>
                </h3>
                <div class="border border-gray-200 rounded overflow-hidden">
                  <table class="w-full text-left border-collapse">
                    <thead>
                      <tr class="bg-gray-100 text-gray-700 text-[11px] font-bold uppercase border-b border-gray-200">
                        <th class="py-2.5 px-3 text-center w-10">No</th>
                        <th class="py-2.5 px-3">Tanggal</th>
                        <th class="py-2.5 px-3">Komponen</th>
                        <th class="py-2.5 px-3">Kode Part OEM</th>
                        <th class="py-2.5 px-3 text-right">Odometer</th>
                        <th class="py-2.5 px-3">Bengkel Pelaksana</th>
                      </tr>
                    </thead>
                    <tbody id="pdf-table-body" class="divide-y divide-gray-100">
                      <!-- Populated dynamically via App.renderPdfSheet() -->
                    </tbody>
                  </table>
                </div>

                <!-- Empty state notice (FR-052) -->
                <div id="pdf-empty-notice" class="hidden py-8 text-center text-gray-500 text-xs bg-gray-50 border border-gray-200 rounded mt-2">
                  Belum ada riwayat penggantian suku cadang yang tercatat pada sepeda motor ini (FR-052).
                </div>
              </div>

              <!-- Official Mandated Legal Disclaimer (FR-053 & R8) -->
              <div class="border-t-2 border-gray-200 pt-4 mt-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-[11px] text-gray-500">
                <div class="space-y-1 max-w-lg">
                  <div class="font-bold text-gray-700 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs text-primary">policy</span>
                    Klausul &amp; Batasan Tanggung Jawab Hukum (Sesuai Ketentuan FR-053):
                  </div>
                  <p class="leading-relaxed">
                    Data riwayat servis di atas dicatat secara mandiri oleh pemilik kendaraan melalui aplikasi MotoCare Digital dan tidak diverifikasi langsung oleh pihak bengkel resmi pabrikan. Dokumen ini sah sebagai rekam jejak pribadi dan referensi transaksi jual beli.
                  </p>
                </div>
                <div class="text-center sm:text-right shrink-0">
                  <div class="w-20 h-20 rounded-full border-2 border-dashed border-red-700/40 text-red-800 flex flex-col items-center justify-center p-1 mx-auto sm:ml-auto">
                    <span class="text-[9px] font-bold uppercase">MOTOCARE</span>
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    <span class="text-[8px] font-mono">DIGITAL</span>
                  </div>
                  <span class="text-[9px] text-gray-400 block mt-1">Dicetak dari MotoCare Digital Web</span>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ====================================================== -->
    <!-- VIEW 5: PENDAFTARAN MOTOR BARU (UC-02, FR-010 - FR-014) -->
    <!-- ====================================================== -->
    <section id="view-daftar-motor" class="tab-content w-full">
      <div class="max-w-2xl mx-auto px-margin md:px-margin-md py-space-xl">
        <div class="bg-surface-container-lowest p-space-xl rounded-xl shadow-sm border border-[#e4beba]/40 flex flex-col gap-space-lg">
          
          <div class="space-y-1 text-center">
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-2">
              <span class="material-symbols-outlined text-2xl">two_wheeler</span>
            </div>
            <h1 class="font-headline-md text-headline-md text-on-surface font-bold">Pendaftaran Sepeda Motor</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">
              Pilih tipe motor dari katalog master untuk menduplikasi standar interval penggantian pabrikan (BR-009).
            </p>
          </div>

          <form onsubmit="event.preventDefault(); App.submitRegisterMotor();" class="space-y-space-md">
            
            <!-- Cascading Merek Motor -->
            <div>
              <label for="reg-brand-select" class="block font-label-md text-label-md font-semibold text-on-surface mb-1">Merek Sepeda Motor</label>
              <select id="reg-brand-select" class="w-full bg-surface-container-low text-on-surface p-3 rounded-lg border border-[#e4beba]/60 focus:outline-none focus:ring-2 focus:ring-primary font-medium" required>
                <option value="">Memuat katalog merek...</option>
              </select>
            </div>

            <!-- Cascading Tipe Motor -->
            <div>
              <label for="reg-type-select" class="block font-label-md text-label-md font-semibold text-on-surface mb-1">Tipe &amp; Varian Motor</label>
              <select id="reg-type-select" disabled class="w-full bg-surface-container-low text-on-surface p-3 rounded-lg border border-[#e4beba]/60 focus:outline-none focus:ring-2 focus:ring-primary font-medium disabled:opacity-50" required>
                <option value="">-- Pilih Merek Terlebih Dahulu --</option>
              </select>
            </div>

            <!-- Nomor Polisi -->
            <div>
              <label for="reg-plate-input" class="block font-label-md text-label-md font-semibold text-on-surface mb-1">Nomor Polisi (Plat Motor)</label>
              <input type="text" id="reg-plate-input" placeholder="Contoh: B 4821 KRO" class="w-full bg-surface-container-low text-on-surface p-3 rounded-lg border border-[#e4beba]/60 focus:outline-none focus:ring-2 focus:ring-primary font-mono uppercase" required/>
            </div>

            <!-- Odometer Saat Ini -->
            <div>
              <label for="reg-odo-input" class="block font-label-md text-label-md font-semibold text-on-surface mb-1">Angka Odometer Saat Ini (km)</label>
              <input type="number" id="reg-odo-input" min="0" max="999999" placeholder="Contoh: 12000" class="w-full bg-surface-container-low text-on-surface p-3 rounded-lg border border-[#e4beba]/60 focus:outline-none focus:ring-2 focus:ring-primary font-mono font-bold" required/>
              <p class="text-xs text-on-surface-variant mt-1">Rentang 0 - 999.999 km sesuai aturan BR-014.</p>
            </div>

            <!-- Optional Initial Custom Baselines Accordion (FR-013) -->
            <details class="bg-surface-container-low p-space-md rounded-lg border border-[#e4beba]/40">
              <summary class="font-label-md text-label-md font-semibold text-on-surface cursor-pointer flex items-center justify-between">
                <span>Pengaturan Penggantian Awal (Opsional - FR-013)</span>
                <span class="material-symbols-outlined text-sm">expand_more</span>
              </summary>
              <div class="pt-space-md" id="reg-custom-baselines-container">
                <p class="text-xs text-on-surface-variant">Pilih tipe motor untuk melihat suku cadang bawaan.</p>
              </div>
            </details>

            <button type="submit" class="w-full bg-primary hover:bg-primary-container text-on-primary py-3 rounded-lg font-label-lg text-label-lg font-semibold shadow-sm transition-all flex items-center justify-center gap-2">
              <span class="material-symbols-outlined">two_wheeler</span>
              <span>Daftarkan Sepeda Motor</span>
            </button>
          </form>

        </div>
      </div>
    </section>

    <!-- ====================================================== -->
    <!-- VIEW 6: ADMIN DATA MASTER (UC-07, FR-060 - FR-063) -->
    <!-- ====================================================== -->
    <section id="view-admin" class="tab-content w-full">
      <div class="max-w-[1320px] mx-auto w-full px-margin md:px-margin-md lg:px-margin-lg py-space-lg flex flex-col gap-space-lg">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md bg-surface-container-low p-space-md rounded-xl border border-surface-container-high">
          <div>
            <span class="bg-primary-fixed text-primary px-2 py-0.5 rounded text-xs font-bold uppercase">Hak Akses Administrator</span>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight mt-1">Pengelolaan Data Master</h1>
            <p class="font-body-md text-xs text-on-surface-variant mt-0.5">
              Kelola katalog tipe motor dan standar suku cadang pabrikan (FR-060 - FR-063). Perubahan master tidak menimpa status snapshot motor terdaftar pengguna (BR-009).
            </p>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="App.openAddMotorModal()" class="bg-surface-container-lowest hover:bg-surface-container-high text-on-surface px-space-md py-2 rounded-lg font-label-md text-xs border border-[#e4beba]/60 flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">add</span> Tambah Tipe Motor
            </button>
            <button onclick="App.openAddPartModal()" class="bg-primary hover:bg-primary-container text-on-primary px-space-md py-2 rounded-lg font-label-md text-xs font-semibold flex items-center gap-1 shadow-sm">
              <span class="material-symbols-outlined text-sm">add_circle</span> Tambah Master Part
            </button>
          </div>
        </div>

        <!-- Master Motor Table -->
        <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-[#e4beba]/40 p-space-md space-y-space-sm">
          <h2 class="font-title-lg text-title-lg font-bold text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">two_wheeler</span>
            <span>Katalog Master Sepeda Motor (FR-060)</span>
          </h2>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-surface-container-low text-on-surface-variant text-[11px] uppercase font-bold border-b border-surface-container-high">
                  <th class="p-space-sm">ID</th>
                  <th class="p-space-sm">Merek</th>
                  <th class="p-space-sm">Tipe</th>
                  <th class="p-space-sm">Varian</th>
                  <th class="p-space-sm">Terdaftar</th>
                  <th class="p-space-sm">Status</th>
                  <th class="p-space-sm text-right">Aksi</th>
                </tr>
              </thead>
              <tbody id="admin-motor-table-body">
                <!-- Dynamic admin motors -->
              </tbody>
            </table>
          </div>
        </div>

        <!-- Master Part Table -->
        <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-[#e4beba]/40 p-space-md space-y-space-sm">
          <h2 class="font-title-lg text-title-lg font-bold text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">build</span>
            <span>Standar Komponen &amp; Suku Cadang (FR-061)</span>
          </h2>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-surface-container-low text-on-surface-variant text-[11px] uppercase font-bold border-b border-surface-container-high">
                  <th class="p-space-sm">ID</th>
                  <th class="p-space-sm">Tipe Motor</th>
                  <th class="p-space-sm">Nama Komponen</th>
                  <th class="p-space-sm">Interval km</th>
                  <th class="p-space-sm">Interval bln</th>
                  <th class="p-space-sm">Kode Part OEM</th>
                  <th class="p-space-sm">Status</th>
                  <th class="p-space-sm text-right">Aksi</th>
                </tr>
              </thead>
              <tbody id="admin-part-table-body">
                <!-- Dynamic admin parts -->
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </section>

  </main>

  <!-- ======================================================== -->
  <!-- MODAL 1: ONE-CLICK RESET "SUDAH DIGANTI" (FR-030 - FR-033, BR-008) -->
  <!-- ======================================================== -->
  <div id="modal-reset-component" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-sm hidden items-center justify-center p-margin">
    <div class="bg-surface-container-lowest rounded-xl max-w-md w-full shadow-xl overflow-hidden border border-[#e4beba]/40">
      
      <!-- Modal Header -->
      <div class="bg-surface-container-low px-space-lg py-space-md flex items-center justify-between border-b border-surface-container-high">
        <div class="flex items-center gap-space-xs text-on-surface">
          <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
          <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Konfirmasi Penggantian Komponen</h3>
        </div>
        <button onclick="App.closeResetModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors" type="button">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-space-lg space-y-space-md">
        <div>
          <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Komponen Yang Diganti</span>
          <p id="reset-comp-name" class="font-title-lg text-title-lg text-on-surface font-semibold mt-0.5">Oli Mesin</p>
          <p id="reset-part-code" class="font-mono text-label-sm text-on-surface-variant">Kode Part: -</p>
        </div>

        <div class="bg-surface-container-low p-space-md rounded-lg space-y-2 font-mono text-body-sm border border-surface-container-high/60">
          <div class="flex justify-between items-center text-on-surface-variant">
            <span>Odometer Tercatat:</span>
            <span id="reset-current-odo-val" class="font-bold text-on-surface font-mono">11.200 km</span>
          </div>
          <div class="flex justify-between items-center text-on-surface-variant">
            <span>Status Baru:</span>
            <span class="font-bold text-secondary font-mono flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">verified</span> Hijau (Kondisi Baik)
            </span>
          </div>
        </div>

        <!-- Editable fields for service record -->
        <div class="space-y-3">
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Odometer Saat Penggantian (km)</label>
            <input type="number" id="reset-odo-input" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-sm"/>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-xs font-semibold text-on-surface mb-1">Tanggal Penggantian</label>
              <input type="date" id="reset-date-input" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs"/>
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface mb-1">Estimasi Biaya (Rp)</label>
              <input type="number" id="reset-biaya-input" value="55000" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs"/>
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Bengkel Pelaksana</label>
            <input type="text" id="reset-bengkel-input" value="AHASS 07123 Daya Motor Depok" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Catatan Tambahan (Opsional)</label>
            <input type="text" id="reset-catatan-input" value="Penggantian rutin berkala" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs"/>
          </div>
        </div>

        <div class="bg-surface-container-high/60 p-space-sm rounded text-body-sm text-on-surface-variant flex items-start gap-space-xs">
          <span class="material-symbols-outlined text-primary text-base shrink-0 mt-0.5">info</span>
          <p class="text-[12px] leading-tight">
            Setelah dikonfirmasi, baseline odometer dan tanggal komponen ini akan disetel ulang per hari ini (BR-008). Catatan akan otomatis tersimpan dalam Buku Servis Digital (FR-032).
          </p>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="bg-surface-container-low px-space-lg py-space-md flex items-center justify-end gap-space-sm border-t border-surface-container-high">
        <button onclick="App.closeResetModal()" class="px-space-md py-2.5 rounded-lg text-on-surface-variant hover:text-on-surface font-label-md text-label-md transition-colors" type="button">
          Batal
        </button>
        <button onclick="App.submitResetComponent()" class="flex items-center gap-space-xs bg-primary hover:bg-primary-container text-on-primary px-space-lg py-2.5 rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all" type="button">
          <span class="material-symbols-outlined text-base">check</span>
          <span>Ya, Catat Selesai</span>
        </button>
      </div>

    </div>
  </div>

  <!-- ======================================================== -->
  <!-- MODAL 2: UPDATE ODOMETER CEPAT (FR-020, FR-021, BR-014) -->
  <!-- ======================================================== -->
  <div id="modal-update-odometer" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-sm hidden items-center justify-center p-margin">
    <div class="bg-surface-container-lowest rounded-xl max-w-md w-full shadow-xl overflow-hidden border border-[#e4beba]/40">
      
      <div class="bg-surface-container-low px-space-lg py-space-md flex items-center justify-between border-b border-surface-container-high">
        <div class="flex items-center gap-space-xs text-on-surface">
          <span class="material-symbols-outlined text-primary text-xl">speed</span>
          <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Perbarui Odometer Motor</h3>
        </div>
        <button onclick="App.closeOdoModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors" type="button">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div class="p-space-lg space-y-space-md">
        <div>
          <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1" for="new-odo-input">
            Angka Odometer Baru (km)
          </label>
          <div class="relative">
            <input id="new-odo-input" type="number" max="999999" class="w-full bg-surface-container-lowest text-on-surface px-space-md py-3 rounded-lg font-mono text-telemetry-num border border-[#e4beba]/60 focus:outline-none focus:ring-2 focus:ring-primary shadow-inner"/>
            <span class="absolute right-4 top-1/2 -translate-y-1/2 font-mono text-on-surface-variant font-bold text-title-md">km</span>
          </div>
          <p id="modal-odo-error" class="font-body-sm text-[12px] text-error mt-1 hidden flex items-center gap-1">
            <span class="material-symbols-outlined text-xs">error</span>
            <span>Angka tidak boleh lebih kecil dari odometer terakhir.</span>
          </p>
        </div>

        <div class="bg-surface-container-low p-space-sm rounded text-body-sm text-on-surface-variant space-y-1 border border-surface-container-high">
          <div class="flex justify-between text-[12px]">
            <span>Nilai saat ini:</span>
            <span id="modal-curr-odo-display" class="font-mono font-bold text-on-surface">11.200 km</span>
          </div>
          <div class="flex justify-between text-[12px]">
            <span>Kenaikan tercatat:</span>
            <span id="modal-odo-delta-display" class="font-mono font-bold text-secondary">+150 km</span>
          </div>
          <p class="text-[11px] text-on-surface-variant pt-1 border-t border-surface-container-high/60">
            *Ketentuan BR-014: Odometer bilangan bulat 0-999.999 km; penambahan lebih dari 5.000 km memicu konfirmasi keamanan lonjakan.
          </p>
        </div>
      </div>

      <div class="bg-surface-container-low px-space-lg py-space-md flex items-center justify-end gap-space-sm border-t border-surface-container-high">
        <button onclick="App.closeOdoModal()" class="px-space-md py-2.5 rounded-lg text-on-surface-variant hover:text-on-surface font-label-md text-label-md transition-colors" type="button">
          Batal
        </button>
        <button onclick="App.submitOdometer()" class="flex items-center gap-space-xs bg-primary hover:bg-primary-container text-on-primary px-space-lg py-2.5 rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all" type="button">
          <span class="material-symbols-outlined text-base">save</span>
          <span>Simpan &amp; Hitung Ulang</span>
        </button>
      </div>

    </div>
  </div>

  <!-- ======================================================== -->
  <!-- MODAL 3: CATAT SERVIS MANUAL (FR-030) -->
  <!-- ======================================================== -->
  <div id="modal-manual-service" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-sm hidden items-center justify-center p-margin">
    <div class="bg-surface-container-lowest rounded-xl max-w-md w-full shadow-xl overflow-hidden border border-[#e4beba]/40">
      
      <div class="bg-surface-container-low px-space-lg py-space-md flex items-center justify-between border-b border-surface-container-high">
        <div class="flex items-center gap-space-xs text-on-surface">
          <span class="material-symbols-outlined text-primary text-xl">build</span>
          <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Catat Servis Manual</h3>
        </div>
        <button onclick="App.closeManualServiceModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg" type="button">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div class="p-space-lg space-y-3">
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Nama Komponen / Pekerjaan</label>
          <input type="text" id="manual-serv-name" placeholder="Contoh: Kuras Radiator Coolant" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs"/>
        </div>
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Kode Part (Bila ada)</label>
          <input type="text" id="manual-serv-part-code" placeholder="Contoh: 08CLA-M99-11LL1" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs"/>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Odometer (km)</label>
            <input type="number" id="manual-serv-odo" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Tanggal</label>
            <input type="date" id="manual-serv-date" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs"/>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Biaya (Rp)</label>
            <input type="number" id="manual-serv-cost" value="35000" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Bengkel</label>
            <input type="text" id="manual-serv-bengkel" value="AHASS 07123 Daya Motor" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs"/>
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Catatan</label>
          <input type="text" id="manual-serv-notes" placeholder="Catatan servis berkala" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs"/>
        </div>
      </div>

      <div class="bg-surface-container-low px-space-lg py-space-md flex items-center justify-end gap-space-sm border-t border-surface-container-high">
        <button onclick="App.closeManualServiceModal()" class="px-space-md py-2 text-xs text-on-surface-variant font-semibold">Batal</button>
        <button onclick="App.submitManualService()" class="bg-primary hover:bg-primary-container text-on-primary px-space-md py-2 rounded-lg text-xs font-semibold shadow-sm">Simpan Catatan</button>
      </div>

    </div>
  </div>

  <!-- ======================================================== -->
  <!-- MODAL 4: TAMBAH MASTER PART (ADMIN, FR-061) -->
  <!-- ======================================================== -->
  <div id="modal-add-master-part" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-sm hidden items-center justify-center p-margin">
    <div class="bg-surface-container-lowest rounded-xl max-w-md w-full shadow-xl overflow-hidden border border-[#e4beba]/40">
      <div class="bg-surface-container-low px-space-lg py-space-md flex items-center justify-between border-b border-surface-container-high">
        <h3 class="font-title-lg text-title-lg font-bold text-on-surface">Tambah Master Part Baru</h3>
        <button onclick="App.closeAddPartModal()" class="text-on-surface-variant p-1"><span class="material-symbols-outlined">close</span></button>
      </div>
      <div class="p-space-lg space-y-3">
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Tipe Sepeda Motor</label>
          <select id="admin-part-motor-select" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs font-semibold"></select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Nama Komponen</label>
          <input type="text" id="admin-part-nama" placeholder="Contoh: Busi NGK CPR9EA-9" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs"/>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Interval km</label>
            <input type="number" id="admin-part-km" placeholder="Contoh: 8000" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Interval Bulan</label>
            <input type="number" id="admin-part-bulan" placeholder="Contoh: 8" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs"/>
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Kode Part Rekomendasi OEM</label>
          <input type="text" id="admin-part-kode" placeholder="Contoh: 31916-K97-T01" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs uppercase"/>
        </div>
        <p class="text-[11px] text-on-surface-variant">
          *Aturan AC-061.1: Minimal salah satu interval (km atau bulan) wajib bernilai lebih dari 0.
        </p>
      </div>
      <div class="bg-surface-container-low px-space-lg py-space-md flex items-center justify-end gap-space-sm border-t border-surface-container-high">
        <button onclick="App.closeAddPartModal()" class="px-space-md py-2 text-xs text-on-surface-variant font-semibold">Batal</button>
        <button onclick="App.submitAddMasterPart()" class="bg-primary hover:bg-primary-container text-on-primary px-space-md py-2 rounded-lg text-xs font-semibold shadow-sm">Simpan Master Part</button>
      </div>
    </div>
  </div>

  <!-- ======================================================== -->
  <!-- MODAL 5: QUICK ACCOUNT SWITCHER & LOGIN (DEMO PURPOSE) -->
  <!-- ======================================================== -->
  <div id="modal-user-switcher" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-sm hidden items-center justify-center p-margin">
    <div class="bg-surface-container-lowest rounded-xl max-w-md w-full shadow-xl overflow-hidden border border-[#e4beba]/40 animate-in fade-in zoom-in-95 duration-200">
      
      <div class="bg-surface-container-low px-space-lg py-space-md flex items-center justify-between border-b border-surface-container-high">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">account_circle</span>
          <h3 class="font-title-lg text-title-lg font-bold text-on-surface">Akun &amp; Hak Akses</h3>
        </div>
        <button onclick="App.closeUserSwitcher()" class="text-on-surface-variant hover:text-on-surface p-1"><span class="material-symbols-outlined">close</span></button>
      </div>

      <!-- Tab Switcher Header inside Modal -->
      <div class="flex border-b border-surface-container-high bg-surface-container-low text-xs font-semibold">
        <button id="auth-tab-demo" onclick="App.switchAuthModalTab('demo')" class="flex-1 py-2.5 text-center border-b-2 border-primary text-primary transition-all">
          Pilih Akun Demo
        </button>
        <button id="auth-tab-login" onclick="App.switchAuthModalTab('login')" class="flex-1 py-2.5 text-center text-on-surface-variant hover:text-on-surface transition-all">
          Masuk (Login)
        </button>
        <button id="auth-tab-register" onclick="App.switchAuthModalTab('register')" class="flex-1 py-2.5 text-center text-on-surface-variant hover:text-on-surface transition-all">
          Daftar Akun Baru
        </button>
      </div>

      <!-- Tab 1: Demo Switcher -->
      <div id="auth-panel-demo" class="p-space-lg space-y-space-sm">
        <p class="text-xs text-on-surface-variant mb-2">
          Beralih peran instan antara Pengguna dan Administrator (1-Click) untuk menguji seluruh modul PRD:
        </p>

        <!-- Option 1: Dimas P. (Pengguna Utama) -->
        <div onclick="App.switchAccount('dimas@motocare.id')" class="p-3 bg-surface-container-low hover:bg-surface-container-high rounded-xl cursor-pointer transition-all flex items-center justify-between border border-[#e4beba]/30">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold">DP</div>
            <div>
              <div class="font-bold text-sm text-on-surface">Dimas Prasetyo</div>
              <div class="text-xs text-on-surface-variant">dimas@motocare.id • Honda Vario 160</div>
            </div>
          </div>
          <span class="bg-secondary-container/40 text-on-secondary-container text-xs px-2 py-0.5 rounded font-semibold">Pengguna</span>
        </div>

        <!-- Option 2: Admin Tim Hore -->
        <div onclick="App.switchAccount('admin@motocare.id')" class="p-3 bg-surface-container-low hover:bg-surface-container-high rounded-xl cursor-pointer transition-all flex items-center justify-between border border-[#e4beba]/30">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">AD</div>
            <div>
              <div class="font-bold text-sm text-on-surface">Admin Tim Hore</div>
              <div class="text-xs text-on-surface-variant">admin@motocare.id • Akses Master Data</div>
            </div>
          </div>
          <span class="bg-primary-fixed text-primary text-xs px-2 py-0.5 rounded font-semibold">Admin</span>
        </div>

        <!-- Option 3: Budi Santoso (Ojol) -->
        <div onclick="App.switchAccount('budi@motocare.id')" class="p-3 bg-surface-container-low hover:bg-surface-container-high rounded-xl cursor-pointer transition-all flex items-center justify-between border border-[#e4beba]/30">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-tertiary/10 text-tertiary flex items-center justify-center font-bold">BS</div>
            <div>
              <div class="font-bold text-sm text-on-surface">Budi Santoso (Ojol)</div>
              <div class="text-xs text-on-surface-variant">budi@motocare.id • Akun Pengemudi</div>
            </div>
          </div>
          <span class="bg-tertiary-fixed text-on-tertiary-fixed text-xs px-2 py-0.5 rounded font-semibold">Pengguna</span>
        </div>
      </div>

      <!-- Tab 2: Login Form (FR-002) -->
      <div id="auth-panel-login" class="p-space-lg space-y-3 hidden">
        <form onsubmit="event.preventDefault(); App.submitLogin();" class="space-y-3">
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Email</label>
            <input type="email" id="login-email" placeholder="nama@email.com" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary" required/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Password</label>
            <input type="password" id="login-password" placeholder="••••••••" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary" required/>
          </div>
          <button type="submit" class="w-full bg-primary hover:bg-primary-container text-on-primary py-2.5 rounded-lg font-label-md text-xs font-semibold shadow-sm transition-all">
            Masuk ke Akun
          </button>
        </form>
      </div>

      <!-- Tab 3: Register Form (FR-001) -->
      <div id="auth-panel-register" class="p-space-lg space-y-3 hidden">
        <form onsubmit="event.preventDefault(); App.submitRegister();" class="space-y-3">
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Nama Lengkap</label>
            <input type="text" id="reg-user-nama" placeholder="Contoh: Andi Saputra" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary" required/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Email</label>
            <input type="email" id="reg-user-email" placeholder="andi@contoh.id" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary" required/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Password (Minimal 8 Karakter)</label>
            <input type="password" id="reg-user-password" minlength="8" placeholder="Minimal 8 karakter (BR-015)" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary" required/>
          </div>
          <button type="submit" class="w-full bg-primary hover:bg-primary-container text-on-primary py-2.5 rounded-lg font-label-md text-xs font-semibold shadow-sm transition-all">
            Daftar Akun Pengguna
          </button>
        </form>
      </div>

      <div class="bg-surface-container-low px-space-lg py-space-sm flex items-center justify-between text-xs text-on-surface-variant border-t border-surface-container-high">
        <span>MotoCare Digital v0.1</span>
        <button onclick="App.logout()" class="text-error hover:underline flex items-center gap-1 font-semibold">
          <span class="material-symbols-outlined text-sm">logout</span> Logout
        </button>
      </div>

    </div>
  </div>

  <!-- Toast Notification Pill -->
  <div id="toastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
    <div class="bg-inverse-surface text-inverse-on-surface px-space-lg py-space-md rounded-xl shadow-xl flex items-center gap-space-sm font-label-md text-label-md border border-white/10">
      <span class="material-symbols-outlined text-secondary" id="toastIcon">check_circle</span>
      <span id="toastText">Operasi berhasil dijalankan.</span>
    </div>
  </div>

  <!-- ======================================================== -->
  <!-- FOOTER -->
  <!-- ======================================================== -->
  <footer class="w-full bg-surface-container-low shadow-[0_-1px_8px_rgba(0,0,0,0.02)] py-space-lg border-t border-surface-container-high">
    <div class="max-w-[1320px] mx-auto px-margin md:px-margin-md lg:px-margin-lg flex flex-col md:flex-row items-center justify-between gap-space-md text-on-surface-variant font-body-sm text-body-sm">
      <div class="flex items-center gap-2">
        <span class="font-bold text-on-surface">MotoCare Digital</span>
        <span>•</span>
        <span>Sistem Telemetri &amp; Buku Servis Digital Berkala</span>
      </div>
      <div class="flex items-center gap-space-lg">
        <span class="flex items-center gap-space-xs">
          <span class="inline-block w-2 h-2 rounded-full bg-secondary"></span>
          <span>Sistem Servis Normal (WIB)</span>
        </span>
        <button onclick="App.openUserSwitcher()" class="hover:text-primary transition-colors underline cursor-pointer">
          Ganti Akun Demo
        </button>
      </div>
    </div>
  </footer>

  <!-- Application Main Script -->
  <script src="assets/js/app.js"></script>
</body>
</html>
