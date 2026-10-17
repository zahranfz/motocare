<?php
// MotoCare Digital - View: Riwayat Servis (UC-04, FR-034, FR-035, BR-016)
?>

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
              Daftar lengkap log pemeliharaan komponen sepeda motor Anda. Catatan tersimpan permanen dan pembatalan hanya berlaku dalam 24 jam untuk penggantian terakhir.
            </p>
          </div>
          <div class="flex items-center flex-wrap gap-space-sm">
            <a href="buku-servis" data-nav-tab="buku-servis" class="flex items-center gap-space-xs px-space-md py-space-sm bg-surface-container-lowest text-on-surface hover:bg-surface-container-high transition-all rounded-lg shadow-sm font-label-lg text-label-lg border border-[#e4beba]/40">
              <span class="material-symbols-outlined text-primary text-title-lg">picture_as_pdf</span>
              <span>Buku Servis PDF</span>
            </a>
            <button onclick="App.openManualServiceModal()" class="flex items-center gap-space-xs px-space-lg py-space-sm bg-primary text-on-primary hover:bg-primary-container transition-all rounded-lg shadow-md font-label-lg text-label-lg" type="button">
              <span class="material-symbols-outlined text-title-lg">add_circle</span>
              <span>+ Catat Servis Manual</span>
            </button>
          </div>
        </div>

        <!-- Cost Tracker & Telemetry Summary Metric Strip -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
          <!-- Metric 1: Total Biaya Servis -->
          <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm border border-[#e4beba]/30 flex flex-col justify-between gap-space-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-primary"></div>
            <div class="flex items-start justify-between pl-1">
              <div class="flex flex-col">
                <span class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant font-semibold">Total Biaya Servis</span>
                <span id="hist-total-biaya" class="font-telemetry-num text-xl font-bold text-on-surface mt-1 font-mono">Rp 0</span>
              </div>
              <div class="p-2 rounded-lg bg-primary/10 text-primary">
                <span class="material-symbols-outlined text-lg">payments</span>
              </div>
            </div>
            <div class="flex items-center justify-between text-xs text-on-surface-variant pt-1 pl-1 border-t border-surface-container-high/60">
              <span id="hist-total-servis">0 Kali Servis</span>
              <span class="text-primary font-medium">Akumulasi</span>
            </div>
          </div>

          <!-- Metric 2: Rata-Rata Biaya -->
          <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm border border-[#e4beba]/30 flex flex-col justify-between gap-space-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-tertiary"></div>
            <div class="flex items-start justify-between pl-1">
              <div class="flex flex-col">
                <span class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant font-semibold">Rata-rata per Servis</span>
                <span id="hist-avg-biaya" class="font-telemetry-num text-xl font-bold text-on-surface mt-1 font-mono">Rp 0</span>
              </div>
              <div class="p-2 rounded-lg bg-surface-container-high text-tertiary">
                <span class="material-symbols-outlined text-lg">analytics</span>
              </div>
            </div>
            <div class="flex items-center justify-between text-xs text-on-surface-variant pt-1 pl-1 border-t border-surface-container-high/60">
              <span>Per Penggantian</span>
              <span class="text-tertiary font-medium">Rata-rata</span>
            </div>
          </div>

          <!-- Metric 3: Cost Per Kilometer -->
          <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm border border-[#e4beba]/30 flex flex-col justify-between gap-space-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-secondary"></div>
            <div class="flex items-start justify-between pl-1">
              <div class="flex flex-col">
                <span class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant font-semibold">Biaya per Kilometer</span>
                <span id="hist-cost-per-km" class="font-telemetry-num text-xl font-bold text-secondary mt-1 font-mono">Rp 0 / km</span>
              </div>
              <div class="p-2 rounded-lg bg-secondary-container/40 text-on-secondary-container">
                <span class="material-symbols-outlined text-lg">speed</span>
              </div>
            </div>
            <div class="flex items-center justify-between text-xs text-on-surface-variant pt-1 pl-1 border-t border-surface-container-high/60">
              <span>Efisiensi Motor</span>
              <span class="text-secondary font-medium">Cost / km</span>
            </div>
          </div>

          <!-- Metric 4: Lampiran Nota Terlampir -->
          <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm border border-[#e4beba]/30 flex flex-col justify-between gap-space-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-secondary"></div>
            <div class="flex items-start justify-between pl-1">
              <div class="flex flex-col">
                <span class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant font-semibold">Bukti Nota &amp; Struk</span>
                <span id="hist-receipt-count" class="font-telemetry-num text-xl font-bold text-on-surface mt-1 font-mono">0 Terlampir</span>
              </div>
              <div class="p-2 rounded-lg bg-surface-container-high text-secondary">
                <span class="material-symbols-outlined text-lg">receipt_long</span>
              </div>
            </div>
            <div class="flex items-center justify-between text-xs text-on-surface-variant pt-1 pl-1 border-t border-surface-container-high/60">
              <span>Odo: <span id="hist-last-odo">-</span></span>
              <span class="text-secondary font-medium">Terverifikasi</span>
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
                  <th class="p-space-md">Bukti Nota</th>
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
