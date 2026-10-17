<?php
// MotoCare Digital - Partial: Top Header & Mobile Navigation
?>

<body class="bg-surface font-body-md text-on-surface min-h-screen flex flex-col selection:bg-primary/20 selection:text-primary">

  <!-- ======================================================== -->
  <!-- TOP HEADER (Clean, Minimalist, Responsive) -->
  <!-- ======================================================== -->
  <header class="fixed top-0 w-full z-40 bg-surface/95 backdrop-blur-md shadow-[0_1px_4px_rgba(0,0,0,0.03)] border-b border-surface-container-high/60">
    <div class="h-16 max-w-[1320px] mx-auto px-4 md:px-6 flex items-center justify-between gap-4">
      
      <!-- Brand Logo -->
      <a href="#dashboard" class="flex items-center gap-2.5 group shrink-0">
        <div class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
          <span class="material-symbols-outlined text-2xl">two_wheeler</span>
        </div>
        <div class="flex items-center gap-1.5">
          <span class="font-headline-md text-xl tracking-tight text-on-surface font-bold">MotoCare</span>
          <span class="hidden sm:inline-block px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-primary/10 text-primary">Digital</span>
        </div>
      </a>

      <!-- Main Navigation Menu (Clean & Compact) -->
      <nav class="hidden md:flex items-center gap-1 bg-surface-container-low p-1 rounded-xl border border-surface-container-high/60">
        <a href="#dashboard" data-nav-tab="dashboard" class="px-3.5 py-1.5 transition-colors bg-primary text-white font-semibold font-label-md text-label-md rounded-lg shadow-xs">
          Dashboard
        </a>
        <a href="#peta-bengkel" data-nav-tab="peta-bengkel" class="px-3.5 py-1.5 transition-colors text-on-surface-variant font-label-md text-label-md hover:text-on-surface hover:bg-surface-container rounded-lg">
          Bengkel
        </a>
        <a href="#riwayat-servis" data-nav-tab="riwayat-servis" class="px-3.5 py-1.5 transition-colors text-on-surface-variant font-label-md text-label-md hover:text-on-surface hover:bg-surface-container rounded-lg">
          Riwayat
        </a>
        <a href="#buku-servis" data-nav-tab="buku-servis" class="px-3.5 py-1.5 transition-colors text-on-surface-variant font-label-md text-label-md hover:text-on-surface hover:bg-surface-container rounded-lg">
          Buku Servis
        </a>
        <a href="#daftar-motor" data-nav-tab="daftar-motor" class="px-3.5 py-1.5 transition-colors text-on-surface-variant font-label-md text-label-md hover:text-on-surface hover:bg-surface-container rounded-lg">
          Motor Saya
        </a>
        <a href="#admin" id="nav-item-admin" data-nav-tab="admin" class="hidden px-3.5 py-1.5 transition-colors text-on-surface-variant font-label-md text-label-md hover:text-on-surface hover:bg-surface-container rounded-lg">
          Admin
        </a>
      </nav>

      <!-- Quick Actions: Garage Switcher, Odometer & User Profile -->
      <div class="flex items-center gap-2 sm:gap-3">
        
        <!-- Active Bike Selector / Garage Switcher Dropdown -->
        <div class="relative" id="garage-dropdown-container">
          <button onclick="App.toggleGarageDropdown()" id="garage-dropdown-btn" class="flex items-center gap-1.5 bg-surface-container-lowest hover:bg-surface-container text-on-surface px-2.5 sm:px-3 py-1.5 rounded-lg border border-[#e4beba]/60 shadow-xs transition-all cursor-pointer" title="Garasi Saya &amp; Pilih Motor Aktif" type="button">
            <span class="material-symbols-outlined text-primary text-[18px]">two_wheeler</span>
            <span class="font-label-md text-xs font-bold max-w-[100px] sm:max-w-[150px] truncate" id="header-active-bike-name">Vario 160</span>
            <span class="material-symbols-outlined text-on-surface-variant text-[16px]">expand_more</span>
          </button>
          <!-- Garage Dropdown Menu -->
          <div id="garage-dropdown-menu" class="hidden absolute right-0 mt-2 w-72 sm:w-80 bg-surface-container-lowest rounded-xl shadow-2xl border border-[#e4beba]/60 p-2.5 z-50">
            <div class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider pb-2 mb-2 border-b border-surface-container-high flex justify-between items-center">
              <span class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-primary text-sm">garage</span>
                <span>Garasi Sepeda Motor</span>
              </span>
              <span id="garage-motor-count-badge" class="bg-primary/10 text-primary font-mono text-[10px] px-2 py-0.5 rounded-full font-bold">1 Motor</span>
            </div>
            <div id="garage-dropdown-list" class="space-y-1.5 max-h-56 overflow-y-auto">
              <!-- Rendered dynamically -->
            </div>
            <div class="pt-2 mt-2 border-t border-surface-container-high">
              <button onclick="App.openRegisterNewMotorModal()" class="w-full flex items-center justify-center gap-1.5 py-2 px-3 bg-primary hover:bg-primary-container text-on-primary rounded-lg text-xs font-bold shadow-xs transition-all" type="button">
                <span class="material-symbols-outlined text-base">add_circle</span>
                <span>+ Tambah Sepeda Motor Baru</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Live Odometer Button -->
        <button onclick="App.openOdoModal()" class="flex items-center gap-1.5 bg-surface-container-lowest hover:bg-surface-container text-on-surface px-3 py-1.5 rounded-lg border border-surface-container-high/80 shadow-xs transition-all cursor-pointer" title="Perbarui Odometer Motor" type="button">
          <span class="material-symbols-outlined text-primary text-[18px]">speed</span>
          <span class="font-label-md text-xs font-mono font-bold" id="header-active-odometer">11.200 km</span>
        </button>

        <!-- PWA Install Button (Displays when install prompt is triggered) -->
        <button id="btn-pwa-install" onclick="App.installPWA()" class="hidden items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary border border-primary/20 text-xs font-bold hover:bg-primary/20 transition-all shadow-xs cursor-pointer" title="Pasang MotoCare ke Layar Utama HP / Desktop" type="button">
          <span class="material-symbols-outlined text-[18px]">install_mobile</span>
          <span class="hidden lg:inline">Install App</span>
        </button>

        <!-- User Profile Avatar Pill -->
        <div onclick="App.openUserSwitcher()" class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full bg-surface-container-lowest hover:bg-surface-container border border-[#e4beba]/40 shadow-xs cursor-pointer transition-all" title="Ganti Akun Demo / Hak Akses">
          <div class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs user-avatar-letter">
            D
          </div>
          <span class="font-medium text-xs text-on-surface user-display-name">Dimas P.</span>
          <span class="material-symbols-outlined text-on-surface-variant text-[16px]">expand_more</span>
        </div>

      </div>
    </div>
  </header>

  <!-- Mobile Bottom Navigation Bar -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface/95 backdrop-blur-md border-t border-surface-container-high shadow-lg px-2 py-2 flex justify-around items-center">
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
      <span class="material-symbols-outlined text-2xl">two_wheeler</span>
      <span class="text-[10px] font-bold">Motor</span>
    </a>
  </nav>
