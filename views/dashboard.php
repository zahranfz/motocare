<?php
// MotoCare Digital - View: Dashboard Servis (UC-03, UC-04, FR-020 - FR-025)
?>

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
                Urutan prioritas otomatis: <strong>Merah → Kuning → Hijau</strong> berdasarkan sisa jarak tempuh dan batas waktu.
              </p>
            </div>
            <div class="flex items-center gap-space-sm flex-wrap">
              <a href="buku-servis" data-nav-tab="buku-servis" class="flex items-center gap-space-xs bg-surface-container-lowest hover:bg-surface-container-high text-on-surface px-space-md py-2 rounded-lg shadow-sm font-label-md text-label-md transition-all border border-[#e4beba]/40">
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
