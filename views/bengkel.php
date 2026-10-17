<?php
// MotoCare Digital - View: Cari Bengkel Terdekat (UC-05, FR-040 - FR-045)
?>

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
                <span class="material-symbols-outlined text-primary text-xl">near_me</span>
                <span class="font-label-sm text-label-sm uppercase tracking-wider text-primary font-bold">Pencari Bengkel Terdekat</span>
              </div>
              <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Cari Bengkel Motor Terdekat</h1>
              <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
                Menemukan bengkel resmi (AHASS, Yamaha) dan bengkel umum terverifikasi di sekitar lokasi Anda.
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

              <!-- Geolocation request button -->
              <button onclick="App.requestUserLocation()" title="Gunakan Lokasi GPS Saya" class="p-2 hover:bg-surface-container rounded-lg text-on-surface-variant hover:text-primary transition-colors" type="button">
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
              <span>Klik pada peta untuk memilih lokasi pencarian manual</span>
            </div>
          </div>
        </div>

        <!-- Split Screen: Workshop List (Left) & Leaflet Map (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
          
          <!-- Workshop Listing Column (5 cols on lg) -->
          <div class="lg:col-span-5 flex flex-col gap-space-sm order-1 lg:order-1">
            <div class="flex items-center justify-between px-space-xs mb-space-xs">
              <span class="font-label-md text-label-md font-bold uppercase tracking-wider text-on-surface-variant">Daftar Terdekat (Urut Jarak)</span>
              <span id="workshop-result-count" class="font-label-sm text-label-sm text-on-surface-variant">Menampilkan bengkel terdekat...</span>
            </div>
            
            <div id="workshop-list-container" class="flex flex-col gap-space-sm max-h-[420px] lg:max-h-[640px] overflow-y-auto overscroll-contain pr-1">
              <!-- Dynamically populated from App.renderWorkshopList() -->
            </div>
          </div>

          <!-- Interactive Leaflet Map Column (7 cols on lg) -->
          <div class="lg:col-span-7 order-2 lg:order-2 lg:sticky lg:top-24">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-[#e4beba]/40 overflow-hidden relative">
              <div id="leaflet-map" class="w-full h-[320px] sm:h-[380px] lg:h-[580px] z-10"></div>
              <!-- Floating Legend -->
              <div class="absolute bottom-2 left-2 sm:bottom-4 sm:left-4 z-20 bg-surface-container-lowest/90 backdrop-blur-md px-2 sm:px-3 py-2 rounded-lg shadow-md border border-[#e4beba]/60 flex flex-wrap items-center gap-x-3 gap-y-1 text-[10px] sm:text-xs">
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
