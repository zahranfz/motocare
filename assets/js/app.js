/**
 * MotoCare Digital - Application Core JavaScript
 * Implements all PRD Requirements, Calculations (BR-001 - BR-007),
 * Leaflet Workshop Map, html2pdf.js Export, and Reactive State.
 */

const App = {
  state: {
    user: null,
    motor: null,
    userMotors: [],
    activeMotorId: null,
    components: [],
    summary: { merah: 0, kuning: 0, hijau: 0, total: 0 },
    historyRecords: [],
    historyMetrics: {},
    resetNotaBase64: null,
    manualNotaBase64: null,
    catalog: [],
    workshops: [],
    workshopLocation: { lat: -6.3728, lng: 106.8335 }, // Margonda Depok default
    workshopRadius: 5,
    workshopFilter: 'all',
    componentFilter: 'all',
    currentTab: 'dashboard',
    activeResetPart: null,
    leafletMap: null,
    mapMarkers: [],
    userMarker: null,
    zoomLevel: 100,
    deferredInstallPrompt: null,
  },

  init() {
    this.bindEvents();
    this.handleRoute(false);
    window.addEventListener('popstate', (e) => {
      const tab = e.state && e.state.tab ? e.state.tab : this.parseCurrentRoute();
      this.switchTab(tab, false);
    });
    window.addEventListener('hashchange', () => this.handleRoute(true));
    window.addEventListener('click', (e) => {
      const garageContainer = document.getElementById('garage-dropdown-container');
      const garageMenu = document.getElementById('garage-dropdown-menu');
      if (garageContainer && garageMenu && !garageContainer.contains(e.target)) {
        garageMenu.classList.add('hidden');
      }
    });

    // PWA Service Worker Registration
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('sw.js')
          .then(reg => console.log('MotoCare PWA ServiceWorker aktif:', reg.scope))
          .catch(err => console.warn('PWA ServiceWorker error:', err));
      });
    }

    // PWA Install Prompt Listener
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      this.state.deferredInstallPrompt = e;
      const installBtn = document.getElementById('btn-pwa-install');
      if (installBtn) {
        installBtn.classList.remove('hidden');
        installBtn.classList.add('flex');
      }
    });

    window.addEventListener('appinstalled', () => {
      this.state.deferredInstallPrompt = null;
      const installBtn = document.getElementById('btn-pwa-install');
      if (installBtn) installBtn.classList.add('hidden');
      this.showToast('MotoCare Digital berhasil dipasang di perangkat Anda!', 'check_circle');
    });

    this.loadCurrentUser().then(() => {
      this.refreshData();
    });
  },

  // ====================================================
  // API HELPER
  // ====================================================
  async api(endpoint, method = 'GET', data = null) {
    const options = {
      method,
      headers: {
        'Accept': 'application/json',
      },
    };

    if (data && method === 'POST') {
      options.headers['Content-Type'] = 'application/json';
      options.body = JSON.stringify(data);
    }

    try {
      const res = await fetch(`api/index.php?action=${endpoint}`, options);
      const json = await res.json();
      return json;
    } catch (err) {
      console.error('API Error:', err);
      this.showToast('Gagal terhubung ke server', 'error');
      return { success: false, error: err.message };
    }
  },

  // ====================================================
  // ROUTING & NAVIGATION (Clean HTML5 History API)
  // ====================================================
  parseCurrentRoute() {
    if (window.initialRoute) {
      const init = window.initialRoute;
      window.initialRoute = null;
      return this.normalizeRoute(init);
    }
    const urlParams = new URLSearchParams(window.location.search);
    let p = urlParams.get('page') || urlParams.get('route') || urlParams.get('view');
    if (p) return this.normalizeRoute(p);

    // Extract path: e.g. /motocare/bengkel -> bengkel
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    const lastPart = pathParts[pathParts.length - 1];
    if (lastPart && lastPart !== 'index.php') {
      return this.normalizeRoute(lastPart);
    }

    if (window.location.hash) {
      return this.normalizeRoute(window.location.hash.replace('#', ''));
    }

    return 'dashboard';
  },

  normalizeRoute(slug) {
    if (!slug) return 'dashboard';
    slug = String(slug).toLowerCase().trim().replace(/[^a-z0-9_-]/g, '');
    if (slug === 'bengkel') return 'peta-bengkel';
    if (slug === 'riwayat') return 'riwayat-servis';
    if (slug === 'pdf') return 'buku-servis';
    if (slug === 'motor') return 'daftar-motor';
    if (['dashboard', 'peta-bengkel', 'riwayat-servis', 'buku-servis', 'daftar-motor', 'admin'].includes(slug)) {
      return slug;
    }
    return 'dashboard';
  },

  getSlugForTab(tabId) {
    if (tabId === 'peta-bengkel') return 'bengkel';
    if (tabId === 'riwayat-servis') return 'riwayat';
    return tabId;
  },

  handleRoute(push = false) {
    const tabId = this.parseCurrentRoute();
    this.switchTab(tabId, push);
  },

  switchTab(tabId, updateHistory = true) {
    this.state.currentTab = tabId;
    const slug = this.getSlugForTab(tabId);

    // Clean URL without hash
    if (updateHistory) {
      if (!window.location.pathname.endsWith('/' + slug) && !window.location.pathname.endsWith('/' + tabId)) {
        window.history.pushState({ tab: tabId }, '', slug);
      }
    } else {
      // Replace state to ensure no leftover index.php or #hash in URL bar
      if (window.location.hash || window.location.pathname.includes('index.php') || window.location.search) {
        window.history.replaceState({ tab: tabId }, '', slug);
      }
    }

    // Toggle tab panels
    document.querySelectorAll('.tab-content').forEach(el => {
      el.classList.remove('active');
    });
    const target = document.getElementById(`view-${tabId}`);
    if (target) {
      target.classList.add('active');
    }

    // Toggle header nav links
    document.querySelectorAll('header nav a[data-nav-tab]').forEach(el => {
      const activeClass = "bg-primary text-white font-semibold font-label-md text-label-md rounded-lg shadow-xs";
      const inactiveClass = "text-on-surface-variant font-label-md text-label-md hover:text-on-surface hover:bg-surface-container rounded-lg";
      if (el.getAttribute('data-nav-tab') === tabId) {
        el.className = `px-3.5 py-1.5 transition-colors ${activeClass}`;
      } else {
        el.className = `px-3.5 py-1.5 transition-colors ${inactiveClass}`;
      }
    });

    // Lazy load specific view content
    if (tabId === 'peta-bengkel') {
      setTimeout(() => this.initOrUpdateMap(), 100);
    } else if (tabId === 'riwayat-servis') {
      this.loadHistory();
    } else if (tabId === 'buku-servis') {
      this.renderPdfSheet();
    } else if (tabId === 'daftar-motor') {
      this.loadCatalog();
    } else if (tabId === 'admin') {
      this.loadAdminData();
    }
  },

  // ====================================================
  // DATA LOADING
  // ====================================================
  async loadCurrentUser() {
    const res = await this.api('get_current_user');
    if (res.success && res.user) {
      this.state.user = res.user;
      this.updateUserUI();
    }
  },

  updateUserUI() {
    const u = this.state.user;
    if (!u) return;

    const nameEls = document.querySelectorAll('.user-display-name');
    nameEls.forEach(el => el.textContent = u.nama ? u.nama.split(' ')[0] : 'User');

    const avatarLetters = document.querySelectorAll('.user-avatar-letter');
    avatarLetters.forEach(el => {
      el.textContent = u.nama ? u.nama.charAt(0).toUpperCase() : 'U';
    });

    const roleBadgeEls = document.querySelectorAll('.user-role-badge');
    roleBadgeEls.forEach(el => {
      if (u.role === 'admin') {
        el.textContent = 'Admin';
        el.className = 'font-label-sm text-label-sm text-primary bg-primary-fixed px-space-xs rounded font-semibold user-role-badge';
      } else {
        el.textContent = 'User';
        el.className = 'font-label-sm text-label-sm text-secondary bg-secondary-container/30 px-space-xs rounded font-semibold user-role-badge';
      }
    });

    // Toggle Admin Navigation Tab
    const adminNav = document.getElementById('nav-item-admin');
    if (adminNav) {
      if (u.role === 'admin') {
        adminNav.classList.remove('hidden');
      } else {
        adminNav.classList.add('hidden');
      }
    }
  },

  async refreshData() {
    const res = await this.api('get_dashboard');
    if (res.success) {
      this.state.motor = res.motor;
      this.state.userMotors = res.user_motors || [];
      this.state.activeMotorId = res.active_motor_id;
      this.state.components = res.components || [];
      this.state.summary = res.summary || { merah: 0, kuning: 0, hijau: 0, total: 0 };

      if (!res.has_motor) {
        this.renderEmptyBikeState();
      } else {
        this.renderDashboard();
      }
    }
  },

  // ====================================================
  // DASHBOARD RENDERING & GARAGE SWITCHER
  // ====================================================
  toggleGarageDropdown() {
    const menu = document.getElementById('garage-dropdown-menu');
    if (menu) menu.classList.toggle('hidden');
  },

  renderGarageDropdown() {
    const list = document.getElementById('garage-dropdown-list');
    const countBadge = document.getElementById('garage-motor-count-badge');
    const motors = this.state.userMotors || [];
    if (countBadge) countBadge.textContent = `${motors.length} Motor`;
    if (!list) return;

    if (motors.length === 0) {
      list.innerHTML = `<div class="p-2 text-xs text-on-surface-variant text-center">Belum ada motor di garasi</div>`;
      return;
    }

    list.innerHTML = motors.map(m => {
      const isActive = m.is_active;
      const c = m.counts || { merah: 0, kuning: 0, hijau: 0 };
      let statusBadge = '<span class="text-[10px] text-secondary font-bold flex items-center gap-0.5"><span class="material-symbols-outlined text-xs">check_circle</span> Aman</span>';
      if (c.merah > 0) {
        statusBadge = `<span class="text-[10px] text-error font-bold flex items-center gap-0.5"><span class="material-symbols-outlined text-xs">error</span> ${c.merah} Servis</span>`;
      } else if (c.kuning > 0) {
        statusBadge = `<span class="text-[10px] text-tertiary font-bold flex items-center gap-0.5"><span class="material-symbols-outlined text-xs">warning</span> ${c.kuning} Perhatian</span>`;
      }

      return `
        <div onclick="App.switchActiveMotor(${m.id})" class="p-2 rounded-lg cursor-pointer transition-colors flex items-center justify-between gap-2 ${isActive ? 'bg-primary/10 border border-primary/30' : 'hover:bg-surface-container border border-transparent'}">
          <div class="flex items-center gap-2 overflow-hidden">
            <div class="w-8 h-8 rounded-lg ${isActive ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant'} flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined text-sm">two_wheeler</span>
            </div>
            <div class="overflow-hidden">
              <div class="text-xs font-bold text-on-surface truncate">${this.escapeHtml(m.merek)} ${this.escapeHtml(m.tipe)}</div>
              <div class="text-[10px] font-mono text-on-surface-variant">${m.nomor_polisi || '-'} • ${Number(m.odometer_saat_ini).toLocaleString('id-ID')} km</div>
            </div>
          </div>
          <div class="shrink-0 flex flex-col items-end">
            ${statusBadge}
            ${isActive ? '<span class="text-[9px] font-bold text-primary uppercase tracking-wider">Aktif</span>' : ''}
          </div>
        </div>
      `;
    }).join('');
  },

  async switchActiveMotor(motorId) {
    const menu = document.getElementById('garage-dropdown-menu');
    if (menu) menu.classList.add('hidden');

    this.showToast('Memuat data sepeda motor...', 'refresh');
    const res = await this.api('switch_motor', 'POST', { motor_id: motorId });
    if (res.success) {
      await this.refreshData();
      if (this.state.currentTab === 'riwayat-servis') {
        await this.loadHistory();
      } else if (this.state.currentTab === 'buku-servis') {
        await this.renderPdfSheet();
      }
      this.showToast(res.message || 'Sepeda motor aktif berhasil diganti', 'check_circle');
    } else {
      this.showToast(res.error || 'Gagal mengganti sepeda motor', 'error');
    }
  },

  openRegisterNewMotorModal() {
    const menu = document.getElementById('garage-dropdown-menu');
    if (menu) menu.classList.add('hidden');
    this.switchTab('daftar-motor');
  },

  renderEmptyBikeState() {
    const banner = document.getElementById('dashboard-hero-banner');
    if (banner) {
      banner.innerHTML = `
        <div class="bg-surface-container-lowest rounded-2xl p-8 shadow-sm border border-[#e4beba]/60 text-center flex flex-col items-center justify-center gap-space-md">
          <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-4xl">two_wheeler</span>
          </div>
          <h2 class="font-headline-md text-headline-md text-on-surface">Belum Ada Sepeda Motor Terdaftar</h2>
          <p class="font-body-md text-body-md text-on-surface-variant max-w-md">
            Daftarkan sepeda motor Anda sekarang untuk mulai memantau masa pakai komponen dan jadwal perawatan berkala.
          </p>
          <button onclick="App.switchTab('daftar-motor')" class="bg-primary hover:bg-primary-container text-on-primary px-space-lg py-3 rounded-xl shadow-sm font-label-lg font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined">add_circle</span>
            <span>Daftarkan Motor Pertama</span>
          </button>
        </div>
      `;
    }
    const grid = document.getElementById('components-grid');
    if (grid) grid.innerHTML = '';
  },

  renderDashboard() {
    const m = this.state.motor;
    const comps = this.state.components;
    const sum = this.state.summary;

    // 1. Update Header Bike Name and Garage Dropdown
    const headerBikeName = document.getElementById('header-active-bike-name');
    if (headerBikeName && m) {
      headerBikeName.textContent = `${m.merek} ${m.tipe}`;
    }
    this.renderGarageDropdown();

    // 2. Update Header Odometer Pill
    const headerOdo = document.getElementById('header-active-odometer');
    if (headerOdo && m) {
      headerOdo.textContent = `${Number(m.odometer_saat_ini).toLocaleString('id-ID')} km`;
    }

    // 3. Render Hero Banner
    const banner = document.getElementById('dashboard-hero-banner');
    if (banner && m) {
      banner.innerHTML = `
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
                <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight font-bold">${this.escapeHtml(m.merek)} ${this.escapeHtml(m.tipe)} ${m.varian ? `(${this.escapeHtml(m.varian)})` : ''}</h1>
                <span class="bg-surface-container text-on-surface font-mono font-semibold px-2.5 py-0.5 rounded text-label-sm tracking-wider border border-surface-container-high">${this.escapeHtml(m.nomor_polisi || '-')}</span>
                <span class="bg-primary/10 text-primary px-2.5 py-0.5 rounded text-label-sm font-semibold">${m.jenis_motor === 'bebek' ? 'Bebek' : 'Matic'}</span>
                <span class="bg-secondary-container/40 text-on-secondary-container px-2 py-0.5 rounded font-label-sm text-label-sm font-semibold flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm">verified</span> Terverifikasi
                </span>
              </div>
              <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-on-surface-variant font-body-sm text-body-sm">
                <span class="flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm">event_repeat</span> Pendaftaran: ${this.formatDate(m.created_at)}
                </span>
                <span class="w-1 h-1 rounded-full bg-surface-container-highest"></span>
                <span class="flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm">history_toggle_off</span> Terakhir diperbarui: ${m.odo_updated_diff || 'Hari ini'}
                </span>
                <span class="w-1 h-1 rounded-full bg-surface-container-highest"></span>
                <span class="flex items-center gap-1 ${sum.merah > 0 ? 'text-primary font-semibold' : 'text-secondary font-semibold'}">
                  <span class="material-symbols-outlined text-sm">${sum.merah > 0 ? 'notifications_active' : 'check_circle'}</span>
                  ${sum.merah > 0 ? `${sum.merah} Komponen Mendesak` : 'Semua Komponen Terkalibrasi'}
                </span>
              </div>
            </div>
          </div>
          <!-- Live Odometer Counter Card & Actions -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 sm:gap-5 z-10 bg-surface-container-low p-4 md:p-5 rounded-xl border border-surface-container-high/60">
            <div class="flex flex-col pr-1">
              <div class="flex items-center justify-between gap-2 mb-1">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Odometer Aktif</span>
                <span class="bg-surface-container-highest text-on-surface-variant text-[10px] font-semibold px-1.5 py-0.5 rounded font-mono">LIVE WIB</span>
              </div>
              <div class="flex items-baseline gap-1.5">
                <span class="font-telemetry-num text-telemetry-num font-mono text-on-surface tracking-tight" id="heroOdometerVal">${Number(m.odometer_saat_ini).toLocaleString('id-ID')}</span>
                <span class="font-title-md text-title-md text-on-surface-variant font-mono font-bold">km</span>
              </div>
            </div>
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
              <button onclick="App.openOdoModal()" class="flex-1 sm:flex-initial flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-container text-on-primary px-4 py-2.5 rounded-xl shadow-xs transition-all active:translate-y-0.5 font-label-md text-label-md font-semibold cursor-pointer" type="button">
                <span class="material-symbols-outlined text-base">speed</span>
                <span>Update Odo</span>
              </button>
              <button onclick="App.shareWhatsAppReminder()" class="flex-1 sm:flex-initial flex items-center justify-center gap-1.5 bg-[#25D366] hover:bg-[#1ebd5a] text-white px-3.5 py-2.5 rounded-xl shadow-xs transition-all active:translate-y-0.5 font-label-md text-label-md font-semibold cursor-pointer" type="button" title="Kirim ringkasan pengingat servis motor ke WhatsApp">
                <span class="material-symbols-outlined text-base">chat</span>
                <span>Kirim WA</span>
              </button>
            </div>
          </div>
        </div>
      `;
    }

    // 4. Update Status KPI Banner counts
    const kpiMerah = document.getElementById('kpi-merah-count');
    const kpiKuning = document.getElementById('kpi-kuning-count');
    const kpiHijau = document.getElementById('kpi-hijau-count');
    if (kpiMerah) kpiMerah.textContent = sum.merah;
    if (kpiKuning) kpiKuning.textContent = sum.kuning;
    if (kpiHijau) kpiHijau.textContent = sum.hijau;

    // Update Filter Toolbar counts
    const filterAll = document.getElementById('filter-count-all');
    const filterMerah = document.getElementById('filter-count-merah');
    const filterKuning = document.getElementById('filter-count-kuning');
    const filterHijau = document.getElementById('filter-count-hijau');
    if (filterAll) filterAll.textContent = sum.total;
    if (filterMerah) filterMerah.textContent = sum.merah;
    if (filterKuning) filterKuning.textContent = sum.kuning;
    if (filterHijau) filterHijau.textContent = sum.hijau;

    // 5. Render Component Cards with Active Filter
    const activeFilter = this.state.componentFilter || 'all';
    this.filterComponentCards(activeFilter, false);
  },

  filterComponentCards(statusCode = 'all', reRender = true) {
    this.state.componentFilter = statusCode;

    // Update filter pills active state
    document.querySelectorAll('.filter-pill').forEach(btn => {
      if (btn.getAttribute('data-filter') === statusCode) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });

    // Update KPI card active border
    document.querySelectorAll('.kpi-card').forEach(card => {
      if (card.getAttribute('data-kpi-filter') === statusCode) {
        card.classList.add('active-filter');
      } else {
        card.classList.remove('active-filter');
      }
    });

    const comps = this.state.components || [];
    let filtered = comps;
    if (statusCode !== 'all') {
      filtered = comps.filter(c => c.status_code === statusCode);
    }
    this.renderComponentCards(filtered);
  },

  renderComponentCards(components) {
    const grid = document.getElementById('components-grid');
    if (!grid) return;

    if (!components || components.length === 0) {
      grid.innerHTML = `
        <div class="col-span-full bg-surface-container-lowest p-space-xl rounded-2xl border border-[#e4beba]/40 text-center text-on-surface-variant">
          <div class="w-12 h-12 rounded-full bg-surface-container mx-auto flex items-center justify-center text-on-surface-variant mb-2">
            <span class="material-symbols-outlined text-2xl">filter_list_off</span>
          </div>
          <p class="font-title-md text-title-md font-semibold text-on-surface">Tidak ada komponen untuk filter ini</p>
          <button onclick="App.filterComponentCards('all')" class="mt-3 px-4 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface text-label-md font-semibold">
            Tampilkan Semua Komponen
          </button>
        </div>
      `;
      return;
    }

    grid.innerHTML = components.map(c => {
      // Border color and badge styling according to status
      let borderColor = '#016d38'; // green
      let badgeBg = 'bg-secondary-container/40 text-on-secondary-container';
      let progressColor = 'bg-secondary';
      let cardBgGradient = '';
      let secondaryBtn = `
        <button onclick="App.switchTab('peta-bengkel')" title="Cari bengkel terdekat" class="h-10 px-3.5 flex items-center justify-center gap-1.5 bg-surface-container-low hover:bg-surface-container-high text-on-surface-variant rounded-lg font-label-md text-label-md transition-colors" type="button">
          <span class="material-symbols-outlined text-primary text-base">near_me</span>
          <span class="hidden sm:inline">Bengkel</span>
        </button>
      `;

      if (c.status_code === 'merah') {
        borderColor = '#af101a'; // red
        badgeBg = 'bg-error-container text-on-error-container';
        progressColor = 'bg-primary';
        cardBgGradient = 'bg-gradient-to-r from-primary/[0.03] to-transparent';
        secondaryBtn = `
          <button onclick="App.switchTab('peta-bengkel')" title="Cari bengkel resmi terdekat untuk ganti ${c.nama_komponen}" class="h-10 px-3.5 flex items-center justify-center gap-1.5 bg-surface-container-high hover:bg-surface-container-highest text-primary border border-primary/20 rounded-lg font-label-md text-label-md font-semibold transition-colors" type="button">
            <span class="material-symbols-outlined text-base">near_me</span>
            <span class="hidden sm:inline">Bengkel</span>
          </button>
        `;
      } else if (c.status_code === 'kuning') {
        borderColor = '#864700'; // amber
        badgeBg = 'bg-tertiary-fixed text-on-tertiary-fixed';
        progressColor = 'bg-tertiary';
        cardBgGradient = 'bg-gradient-to-r from-tertiary/[0.03] to-transparent';
        secondaryBtn = `
          <button onclick="App.switchTab('peta-bengkel')" title="Cari bengkel resmi terdekat untuk ganti ${c.nama_komponen}" class="h-10 px-3.5 flex items-center justify-center gap-1.5 bg-surface-container-high hover:bg-surface-container-highest text-primary border border-primary/20 rounded-lg font-label-md text-label-md font-semibold transition-colors" type="button">
            <span class="material-symbols-outlined text-base">near_me</span>
            <span class="hidden sm:inline">Bengkel</span>
          </button>
        `;
      }

      // Sisa km formatting
      let sisaKmDisplay = `${Number(c.sisa_km).toLocaleString('id-ID')} km tersisa`;
      let sisaKmClass = 'text-on-surface';
      if (c.sisa_km !== null && c.sisa_km < 0) {
        sisaKmDisplay = `${Math.abs(c.sisa_km).toLocaleString('id-ID')} km melebihi batas`;
        sisaKmClass = 'text-error font-bold font-mono';
      } else if (c.sisa_km === 0) {
        sisaKmDisplay = `Batas 0 km tercapai`;
        sisaKmClass = 'text-error font-bold font-mono';
      } else if (c.status_code === 'kuning') {
        sisaKmClass = 'text-tertiary font-bold font-mono';
      }

      // Sisa hari formatting
      let sisaHariDisplay = `${c.sisa_hari} hari tersisa`;
      let sisaHariClass = 'text-on-surface';
      if (c.sisa_hari !== null && c.sisa_hari < 0) {
        sisaHariDisplay = `Terlambat ${Math.abs(c.sisa_hari)} hari`;
        sisaHariClass = 'text-error font-semibold font-mono';
      } else if (c.sisa_hari === 0) {
        sisaHariDisplay = `Hari ini jatuh tempo`;
        sisaHariClass = 'text-error font-semibold font-mono';
      }

      const cappedWear = Math.min(100, Math.max(0, c.wear_percent || 0));

      return `
        <div class="component-card-tactile p-5 md:p-6 relative overflow-hidden flex flex-col justify-between gap-5 transition-all hover:shadow-lg ${cardBgGradient}">
          <!-- Left Status Perimeter Strip (Tactile Precision) -->
          <div class="absolute left-0 top-0 bottom-0 w-1.5" style="background-color: ${borderColor};"></div>

          <div class="space-y-4 pl-1">
            <!-- Header: Title & Status Badge -->
            <div class="flex items-start justify-between gap-2">
              <div class="space-y-1 min-h-[46px] flex flex-col justify-center">
                <h3 class="font-title-lg text-title-lg text-on-surface font-bold leading-snug line-clamp-2">${c.nama_komponen}</h3>
                <div class="flex items-center gap-1.5 flex-wrap">
                  <span class="font-mono text-label-sm text-on-surface-variant font-semibold">Part: ${c.kode_part || 'Belum tersedia'}</span>
                  ${c.kode_part ? `
                    <button onclick="navigator.clipboard.writeText('${c.kode_part}'); App.showToast('Kode part disalin!', 'content_copy');" title="Salin Kode Part" class="text-on-surface-variant hover:text-primary transition-colors">
                      <span class="material-symbols-outlined text-[14px]">content_copy</span>
                    </button>
                  ` : ''}
                </div>
              </div>
              <span class="shrink-0 px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold flex items-center gap-1 ${badgeBg}">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">${c.status_icon}</span>
                <span>${c.status_text}</span>
              </span>
            </div>

            <!-- Wear Percentage Progress Bar -->
            <div class="space-y-1.5">
              <div class="flex justify-between items-center text-body-sm font-body-sm text-on-surface-variant">
                <span>Tingkat Keausan</span>
                <span class="font-mono font-bold ${c.status_code === 'merah' ? 'text-primary' : 'text-on-surface'}">${c.wear_percent}%</span>
              </div>
              <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 ${progressColor}" style="width: ${cappedWear}%;"></div>
              </div>
            </div>

            <!-- Telemetry Metrics Grid -->
            <div class="grid grid-cols-2 gap-3 bg-surface-container-low/90 p-3.5 rounded-xl border border-surface-container-high/50 font-mono text-body-sm text-on-surface">
              <div class="flex flex-col">
                <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Jarak Tempuh</span>
                <span class="font-semibold text-[13px] ${sisaKmClass} flex items-center min-h-[22px]">${sisaKmDisplay}</span>
                <span class="text-[10px] text-on-surface-variant">Interval: ${c.interval_km ? Number(c.interval_km).toLocaleString('id-ID') + ' km' : '-'}</span>
              </div>
              <div class="flex flex-col border-l border-surface-container-high pl-3">
                <span class="text-[11px] font-sans text-on-surface-variant uppercase tracking-wider font-semibold">Batas Waktu</span>
                <span class="font-semibold text-[13px] ${sisaHariClass} flex items-center min-h-[22px]">${sisaHariDisplay}</span>
                <span class="text-[10px] text-on-surface-variant">Interval: ${c.interval_bulan ? c.interval_bulan + ' bln' : '-'}</span>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center gap-2 pt-2 border-t border-surface-container-high/40 pl-1">
            <button onclick="App.openResetModal(${c.id})" class="flex-1 h-10 flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-container text-on-primary rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all active:translate-y-0.5" type="button">
              <span class="material-symbols-outlined text-base">check</span>
              <span>Sudah Diganti</span>
            </button>
            ${secondaryBtn}
          </div>
        </div>
      `;
    }).join('');
  },

  // ====================================================
  // ONE-CLICK RESET (FR-030 - FR-033, BR-008)
  // ====================================================
  openResetModal(partId) {
    const part = this.state.components.find(c => c.id === partId);
    if (!part) return;

    this.state.activeResetPart = part;
    const motor = this.state.motor;

    const modal = document.getElementById('modal-reset-component');
    if (!modal) return;

    document.getElementById('reset-comp-name').textContent = part.nama_komponen;
    document.getElementById('reset-part-code').textContent = `Kode Part: ${part.kode_part || 'Data belum tersedia'}`;
    document.getElementById('reset-current-odo-val').textContent = `${Number(motor.odometer_saat_ini).toLocaleString('id-ID')} km`;
    document.getElementById('reset-odo-input').value = motor.odometer_saat_ini;
    document.getElementById('reset-date-input').value = new Date().toISOString().split('T')[0];
    this.removeNotaPreview(null, 'reset-nota-file', 'reset-nota-preview', 'reset-nota-preview-container', 'reset-nota-placeholder', 'reset');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  },

  closeResetModal() {
    const modal = document.getElementById('modal-reset-component');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
    this.removeNotaPreview(null, 'reset-nota-file', 'reset-nota-preview', 'reset-nota-preview-container', 'reset-nota-placeholder', 'reset');
    this.state.activeResetPart = null;
  },

  async submitResetComponent() {
    const part = this.state.activeResetPart;
    if (!part) return;

    const odo = parseInt(document.getElementById('reset-odo-input').value, 10);
    const date = document.getElementById('reset-date-input').value;
    const bengkel = document.getElementById('reset-bengkel-input').value || 'AHASS 07123 Daya Motor Depok';
    const biaya = parseInt(document.getElementById('reset-biaya-input').value, 10) || 0;
    const catatan = document.getElementById('reset-catatan-input').value || 'Penggantian rutin berkala';
    const fotoNota = this.state.resetNotaBase64 || null;

    if (isNaN(odo) || odo < 0) {
      this.showToast('Nilai odometer tidak valid', 'error');
      return;
    }

    const res = await this.api('reset_component', 'POST', {
      user_part_status_id: part.id,
      odometer: odo,
      tanggal: date,
      bengkel,
      biaya,
      catatan,
      foto_nota: fotoNota,
    });

    if (res.success) {
      this.closeResetModal();
      this.showToast(res.message, 'check_circle');
      this.refreshData();
    } else {
      this.showToast(res.error || 'Gagal mereset komponen', 'error');
    }
  },

  // ====================================================
  // ODOMETER UPDATE (FR-020, FR-021, BR-014)
  // ====================================================
  openOdoModal() {
    const m = this.state.motor;
    if (!m) return;

    const modal = document.getElementById('modal-update-odometer');
    if (!modal) return;

    const input = document.getElementById('new-odo-input');
    const currValDisplay = document.getElementById('modal-curr-odo-display');
    const deltaDisplay = document.getElementById('modal-odo-delta-display');
    const errorEl = document.getElementById('modal-odo-error');

    currValDisplay.textContent = `${Number(m.odometer_saat_ini).toLocaleString('id-ID')} km`;
    input.value = Number(m.odometer_saat_ini) + 150;
    input.min = m.odometer_saat_ini;
    if (errorEl) errorEl.classList.add('hidden');

    this.calcOdoDelta();

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  },

  closeOdoModal() {
    const modal = document.getElementById('modal-update-odometer');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  },

  calcOdoDelta() {
    const m = this.state.motor;
    if (!m) return;

    const input = document.getElementById('new-odo-input');
    const deltaDisplay = document.getElementById('modal-odo-delta-display');
    const errorEl = document.getElementById('modal-odo-error');

    const val = parseInt(input.value, 10);
    const curr = parseInt(m.odometer_saat_ini, 10);

    if (isNaN(val) || val < curr) {
      if (deltaDisplay) deltaDisplay.textContent = `${(val - curr).toLocaleString('id-ID')} km (Tidak valid)`;
      if (deltaDisplay) deltaDisplay.className = "font-mono font-bold text-error";
      if (errorEl) {
        errorEl.textContent = `Angka tidak boleh lebih kecil dari odometer terakhir (${curr.toLocaleString('id-ID')} km).`;
        errorEl.classList.remove('hidden');
      }
    } else {
      const diff = val - curr;
      if (deltaDisplay) deltaDisplay.textContent = `+${diff.toLocaleString('id-ID')} km`;
      if (deltaDisplay) deltaDisplay.className = "font-mono font-bold text-secondary";
      if (errorEl) errorEl.classList.add('hidden');
    }
  },

  async submitOdometer(confirmJump = false) {
    const m = this.state.motor;
    if (!m) return;

    const input = document.getElementById('new-odo-input');
    const val = parseInt(input.value, 10);

    if (isNaN(val) || val < m.odometer_saat_ini) {
      this.showToast(`Angka tidak boleh lebih kecil dari ${m.odometer_saat_ini.toLocaleString('id-ID')} km`, 'error');
      return;
    }

    const res = await this.api('update_odometer', 'POST', {
      user_motor_id: m.id,
      odometer: val,
      confirm_jump: confirmJump,
    });

    if (res.require_confirmation) {
      const confirmed = confirm(res.message);
      if (confirmed) {
        this.submitOdometer(true);
      }
      return;
    }

    if (res.success) {
      this.closeOdoModal();
      this.showToast(res.message, 'speed');
      this.refreshData();
    } else {
      this.showToast(res.error || 'Gagal memperbarui odometer', 'error');
    }
  },

  // ====================================================
  // WORKSHOP FINDER (LEAFLET + OVERPASS API)
  // ====================================================
  async reverseGeocode(lat, lng) {
    try {
      const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=14&addressdetails=1`, {
        headers: { 'Accept-Language': 'id' }
      });
      if (res.ok) {
        const data = await res.json();
        const addr = data.address || {};
        const road = addr.road || addr.street || addr.pedestrian || '';
        const district = addr.suburb || addr.village || addr.neighbourhood || addr.city_district || addr.hamlet || addr.county || '';
        const city = addr.city || addr.regency || addr.town || addr.municipality || '';
        const state = addr.state || '';
        const displayName = [district, city].filter(Boolean).join(', ') || [road, city].filter(Boolean).join(', ') || data.name || 'Lokasi Terpilih';
        return { road, district, city, state, displayName };
      }
    } catch (err) {
      console.warn('Reverse geocode error:', err);
    }
    return null;
  },

  async initOrUpdateMap() {
    const mapEl = document.getElementById('leaflet-map');
    if (!mapEl) return;

    // Load Leaflet map if not initialized
    if (!this.state.leafletMap) {
      this.state.leafletMap = L.map('leaflet-map', {
        zoomControl: true,
        scrollWheelZoom: true,
      }).setView([this.state.workshopLocation.lat, this.state.workshopLocation.lng], 14);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
      }).addTo(this.state.leafletMap);

      // Handle map click to manually pick location (FR-045)
      this.state.leafletMap.on('click', (e) => {
        this.setManualLocation(e.latlng.lat, e.latlng.lng);
      });

      // Try automatic GPS detection on initial open
      if (navigator.geolocation && !this.state.gpsInitialized) {
        this.state.gpsInitialized = true;
        navigator.geolocation.getCurrentPosition(
          (pos) => {
            this.setManualLocation(pos.coords.latitude, pos.coords.longitude, 'Lokasi GPS Anda');
          },
          (err) => {
            console.log('GPS initial auto-detect skipped:', err.message);
          },
          { timeout: 4000, enableHighAccuracy: false }
        );
      }
    }

    await this.fetchWorkshops();
  },

  async fetchWorkshops(areaInfo = null) {
    const { lat, lng } = this.state.workshopLocation;
    const radius = this.state.workshopRadius;
    const filter = this.state.workshopFilter;

    if (!areaInfo) {
      areaInfo = await this.reverseGeocode(lat, lng);
    }

    if (areaInfo && areaInfo.displayName) {
      const labelEl = document.getElementById('gps-acuan-label');
      if (labelEl) labelEl.textContent = areaInfo.displayName;
    }

    let queryParams = `get_workshops&lat=${lat}&lng=${lng}&radius=${radius}&filter=${filter}`;
    if (areaInfo) {
      if (areaInfo.district) queryParams += `&area=${encodeURIComponent(areaInfo.district)}`;
      if (areaInfo.city) queryParams += `&city=${encodeURIComponent(areaInfo.city)}`;
      if (areaInfo.road) queryParams += `&road=${encodeURIComponent(areaInfo.road)}`;
    }

    const res = await this.api(queryParams);
    if (res.success) {
      this.state.workshops = res.workshops || [];
      this.renderWorkshopList(res.workshops);
      this.renderMapMarkers(res.workshops);

      // Update counters
      const countEl = document.getElementById('workshop-result-count');
      if (countEl) countEl.textContent = `Menampilkan ${res.total_found} bengkel dalam radius ${radius} km`;

      const cntAll = document.getElementById('badge-count-all');
      const cntResmi = document.getElementById('badge-count-resmi');
      const cntUmum = document.getElementById('badge-count-umum');
      if (cntAll) cntAll.textContent = res.counts.all;
      if (cntResmi) cntResmi.textContent = res.counts.resmi;
      if (cntUmum) cntUmum.textContent = res.counts.umum;
    }
  },

  renderMapMarkers(workshops) {
    if (!this.state.leafletMap) return;

    // Clear existing markers
    this.state.mapMarkers.forEach(m => this.state.leafletMap.removeLayer(m));
    this.state.mapMarkers = [];

    if (this.state.userMarker) {
      this.state.leafletMap.removeLayer(this.state.userMarker);
    }

    // Add user position marker
    const userIcon = L.divIcon({
      className: 'custom-leaflet-marker marker-user',
      html: '<span class="material-symbols-outlined text-[18px]">person_pin_circle</span>',
      iconSize: [32, 32],
      iconAnchor: [16, 16],
    });
    this.state.userMarker = L.marker([this.state.workshopLocation.lat, this.state.workshopLocation.lng], { icon: userIcon })
      .addTo(this.state.leafletMap)
      .bindPopup('<strong>Lokasi Anda</strong><br>Titik acuan pencarian GPS');

    // Add workshop markers
    workshops.forEach(w => {
      const isResmi = w.type === 'resmi';
      const markerClass = isResmi ? 'marker-resmi' : 'marker-umum';
      const iconSymbol = isResmi ? 'stars' : 'build';

      const wIcon = L.divIcon({
        className: `custom-leaflet-marker ${markerClass}`,
        html: `<span class="material-symbols-outlined text-[16px]">${iconSymbol}</span>`,
        iconSize: [28, 28],
        iconAnchor: [14, 14],
      });

      const m = L.marker([w.lat, w.lng], { icon: wIcon })
        .addTo(this.state.leafletMap)
        .bindPopup(`
          <div class="p-1 space-y-1.5 min-w-[200px]">
            <span class="font-bold text-xs uppercase px-1.5 py-0.5 rounded ${isResmi ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">${w.brand}</span>
            <h4 class="font-bold text-sm leading-tight text-gray-900 mt-1">${w.name}</h4>
            <p class="text-xs text-gray-600">${w.address}</p>
            <div class="text-xs font-semibold text-gray-800 flex justify-between pt-1">
              <span>Jarak: ${w.distance_text}</span>
              <span>⭐ ${w.rating}</span>
            </div>
            <div class="flex items-center gap-1.5 pt-1">
              <a href="${this.getWorkshopWhatsAppLink(w)}" target="_blank" class="flex-1 text-center bg-[#25D366] hover:bg-[#1ebd5a] text-white py-1.5 px-2 rounded text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[14px]">chat</span> Booking WA
              </a>
              <a href="https://www.google.com/maps/search/?api=1&query=${w.lat},${w.lng}" target="_blank" class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white py-1.5 px-2 rounded text-xs font-semibold transition-all">
                Rute Maps
              </a>
            </div>
          </div>
        `);

      this.state.mapMarkers.push(m);
    });

    this.state.leafletMap.setView([this.state.workshopLocation.lat, this.state.workshopLocation.lng], 14);
  },

  renderWorkshopList(workshops) {
    const listEl = document.getElementById('workshop-list-container');
    if (!listEl) return;

    if (!workshops || workshops.length === 0) {
      listEl.innerHTML = `
        <div class="bg-surface-container-lowest p-space-lg rounded-xl text-center text-on-surface-variant">
          <span class="material-symbols-outlined text-4xl text-on-surface-variant/40 mb-2">location_off</span>
          <p class="font-semibold text-on-surface">Tidak ada bengkel ditemukan dalam radius ${this.state.workshopRadius} km</p>
          <p class="text-body-sm mt-1">Coba perbesar radius pencarian menjadi 10 km atau pilih lokasi lain.</p>
        </div>
      `;
      return;
    }

    listEl.innerHTML = workshops.map(w => {
      const isResmi = w.type === 'resmi';
      return `
        <article onclick="App.focusWorkshop(${w.lat}, ${w.lng})" class="workshop-card relative bg-surface-container-lowest rounded-xl p-space-md shadow-sm transition-all duration-200 cursor-pointer hover:shadow-md border border-[#e4beba]/30">
          <div class="absolute left-0 top-0 bottom-0 w-1.5 rounded-l-xl ${isResmi ? 'bg-primary' : 'bg-secondary'}"></div>
          <div class="flex items-start justify-between gap-space-sm pl-space-xs">
            <div class="space-y-1 flex-1">
              <div class="flex flex-wrap items-center gap-space-xs">
                <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded font-label-sm text-label-sm font-bold ${isResmi ? 'bg-primary/10 text-primary' : 'bg-secondary/10 text-secondary'}">
                  <span class="material-symbols-outlined text-[14px]">${isResmi ? 'stars' : 'verified'}</span>
                  ${w.brand.toUpperCase()}
                </span>
                <span class="font-mono text-label-sm ${isResmi ? 'text-primary' : 'text-secondary'} font-bold">${w.dealer_code}</span>
              </div>
              <h3 class="font-title-lg text-title-lg text-on-surface font-bold leading-tight">${w.name}</h3>
              <p class="font-body-sm text-body-sm text-on-surface-variant flex items-start gap-1">
                <span class="material-symbols-outlined text-[16px] text-on-surface-variant shrink-0 mt-0.5">pin_drop</span>
                <span>${w.address}</span>
              </p>
              <div class="flex flex-wrap items-center gap-x-space-md gap-y-1 pt-1 text-body-sm text-on-surface-variant">
                <span class="flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px]">call</span> ${w.phone}
                </span>
                <span class="flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px]">schedule</span> ${w.hours}
                </span>
              </div>
              <div class="pt-1 text-[11px] font-semibold ${isResmi ? 'text-primary' : 'text-secondary'}">
                ${w.parts_stock}
              </div>
            </div>
            <div class="flex flex-col items-end gap-2 shrink-0">
              <span class="bg-surface-container px-space-sm py-1 rounded font-mono font-bold text-on-surface text-label-md">
                ${w.distance_text}
              </span>
              <div class="flex items-center gap-1.5 mt-1">
                <a href="${this.getWorkshopWhatsAppLink(w)}" target="_blank" onclick="event.stopPropagation()" class="px-2.5 py-1.5 bg-[#25D366] hover:bg-[#1ebd5a] text-white rounded-lg text-xs font-bold flex items-center gap-1 shadow-xs transition-all" title="Booking Servis via WhatsApp">
                  <span class="material-symbols-outlined text-[15px]">chat</span>
                  <span>Booking WA</span>
                </a>
                <a href="https://www.google.com/maps/search/?api=1&query=${w.lat},${w.lng}" target="_blank" onclick="event.stopPropagation()" class="p-1.5 hover:bg-surface-container rounded-lg text-primary border border-surface-container-high transition-colors" title="Buka Rute Navigasi Google Maps">
                  <span class="material-symbols-outlined text-base">directions</span>
                </a>
              </div>
            </div>
          </div>
        </article>
      `;
    }).join('');
  },

  getWorkshopWhatsAppLink(w) {
    const m = this.state.motor;
    const comps = this.state.components || [];
    const urgentComps = comps.filter(c => c.status_code === 'merah' || c.status_code === 'kuning');
    let partList = urgentComps.map(c => `- ${c.nama_komponen} (${c.status_text})`).join('\n');
    if (!partList) partList = '- Servis berkala rutin & ganti oli';

    const bikeInfo = m ? `${m.merek} ${m.tipe} (${m.nomor_polisi || '-'}) • ${Number(m.odometer_saat_ini).toLocaleString('id-ID')} km` : 'Sepeda Motor';
    const text = `Halo ${w.name}, saya ingin reservasi/booking jadwal servis motor melalui MotoCare Digital.\n\n` +
      `🏍️ *Data Kendaraan:* ${bikeInfo}\n` +
      `🛠️ *Rencana Penggantian/Servis:*\n${partList}\n\n` +
      `Apakah besok tersedia slot mekanik? Terima kasih.`;

    const phone = w.wa_phone || '6281298443321';
    return `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
  },

  shareWhatsAppReminder() {
    const m = this.state.motor;
    if (!m) {
      this.showToast('Data motor belum tersedia', 'error');
      return;
    }
    const comps = this.state.components || [];
    const merah = comps.filter(c => c.status_code === 'merah');
    const kuning = comps.filter(c => c.status_code === 'kuning');
    const hijau = comps.filter(c => c.status_code === 'hijau');

    let msg = `🔔 *PENGINGAT SERVIS - MOTOCARE DIGITAL* 🛵\n` +
      `Halo! Berikut ringkasan kesehatan & jadwal servis motor saya:\n\n` +
      `🏍️ *Motor:* ${m.merek} ${m.tipe} ${m.varian ? `(${m.varian})` : ''}\n` +
      `🔢 *Nomor Polisi:* ${m.nomor_polisi || '-'}\n` +
      `📊 *Odometer:* ${Number(m.odometer_saat_ini).toLocaleString('id-ID')} km\n` +
      `📅 *Dicatat pada:* ${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}\n\n`;

    if (merah.length > 0) {
      msg += `🔴 *WAKTUNYA GANTI (MENDESAK):*\n`;
      merah.forEach(c => {
        const sisaKm = c.sisa_km !== null ? (c.sisa_km <= 0 ? `Terlambat ${Math.abs(c.sisa_km)} km` : `Sisa ${c.sisa_km} km`) : '';
        const sisaHari = c.sisa_hari !== null ? (c.sisa_hari <= 0 ? `Terlambat ${Math.abs(c.sisa_hari)} hari` : `Sisa ${c.sisa_hari} hari`) : '';
        const sisa = [sisaKm, sisaHari].filter(Boolean).join(' / ');
        msg += `• ${c.nama_komponen} [${sisa}]\n  Kode Part OEM: ${c.kode_part || '-'}\n`;
      });
      msg += `\n`;
    }

    if (kuning.length > 0) {
      msg += `🟡 *PERLU PERHATIAN (MENDEKATI JATUH TEMPO):*\n`;
      kuning.forEach(c => {
        const sisaKm = c.sisa_km !== null ? `Sisa ${c.sisa_km} km` : '';
        const sisaHari = c.sisa_hari !== null ? `Sisa ${c.sisa_hari} hari` : '';
        const sisa = [sisaKm, sisaHari].filter(Boolean).join(' / ');
        msg += `• ${c.nama_komponen} [${sisa}]\n  Kode Part OEM: ${c.kode_part || '-'}\n`;
      });
      msg += `\n`;
    }

    if (hijau.length > 0) {
      msg += `🟢 *KONDISI BAIK (${hijau.length} Komponen):*\n`;
      msg += hijau.map(c => c.nama_komponen).slice(0, 4).join(', ') + (hijau.length > 4 ? ', dll.' : '') + `\n\n`;
    }

    msg += `💡 Dibuat otomatis melalui MotoCare Digital: ${window.location.origin}${window.location.pathname}`;

    const waUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(msg)}`;
    window.open(waUrl, '_blank');
    this.showToast('Membuka WhatsApp untuk berbagi pengingat...', 'check_circle');
  },

  async installPWA() {
    if (!this.state.deferredInstallPrompt) {
      this.showToast('Aplikasi sudah terpasang atau browser Anda belum mendukung instalasi otomatis.', 'info');
      return;
    }
    this.state.deferredInstallPrompt.prompt();
    const { outcome } = await this.state.deferredInstallPrompt.userChoice;
    if (outcome === 'accepted') {
      this.showToast('Memasang MotoCare Digital...', 'check_circle');
    }
    this.state.deferredInstallPrompt = null;
    const installBtn = document.getElementById('btn-pwa-install');
    if (installBtn) installBtn.classList.add('hidden');
  },

  focusWorkshop(lat, lng) {
    if (!this.state.leafletMap) return;
    this.state.leafletMap.flyTo([lat, lng], 16, { duration: 1 });
  },

  requestUserLocation() {
    if (!navigator.geolocation) {
      this.showToast('Geolocation tidak didukung peramban ini', 'error');
      return;
    }

    this.showToast('Meminta koordinat GPS...', 'my_location');
    navigator.geolocation.getCurrentPosition(
      async (pos) => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        this.showToast('Lokasi GPS berhasil didapatkan!', 'check_circle');
        await this.setManualLocation(lat, lng, 'Lokasi GPS Anda');
      },
      (err) => {
        console.warn('Geolocation denied/error:', err);
        this.showToast('Izin lokasi ditolak. Anda dapat klik peta untuk memilih lokasi manual.', 'info');
      },
      { timeout: 10000, enableHighAccuracy: true }
    );
  },

  async setManualLocation(lat, lng, label = 'Titik Pilihan Manual') {
    this.state.workshopLocation = { lat, lng };
    const labelEl = document.getElementById('gps-acuan-label');
    if (labelEl) labelEl.textContent = 'Memuat lokasi...';

    const areaInfo = await this.reverseGeocode(lat, lng);
    if (labelEl) {
      labelEl.textContent = areaInfo ? areaInfo.displayName : label;
    }

    await this.fetchWorkshops(areaInfo);
  },

  setWorkshopFilter(filterType) {
    this.state.workshopFilter = filterType;
    // Update filter toggle buttons
    ['all', 'resmi', 'umum'].forEach(t => {
      const btn = document.getElementById(`filter-btn-${t}`);
      if (btn) {
        if (t === filterType) {
          btn.className = "flex items-center gap-2 px-space-md py-space-xs rounded-lg font-label-md text-label-md transition-all bg-on-surface text-inverse-on-surface shadow-sm";
        } else {
          btn.className = "flex items-center gap-2 px-space-md py-space-xs rounded-lg font-label-md text-label-md transition-all bg-surface-container-lowest text-on-surface hover:bg-surface-container shadow-sm";
        }
      }
    });
    this.fetchWorkshops();
  },

  setWorkshopRadius(radius) {
    this.state.workshopRadius = parseInt(radius, 10);
    this.fetchWorkshops();
  },

  // ====================================================
  // SERVICE HISTORY (FR-034, FR-035, BR-016, BR-017)
  // ====================================================
  async loadHistory() {
    const res = await this.api('get_history');
    if (res.success) {
      this.state.historyRecords = res.records || [];
      this.state.historyMetrics = res.metrics || {};
      this.renderHistory();
    }
  },

  renderHistory() {
    const records = this.state.historyRecords || [];
    const m = this.state.historyMetrics || {};

    // Update Telemetry & Cost Tracker Strip
    const totalServisEl = document.getElementById('hist-total-servis');
    const totalBiayaEl = document.getElementById('hist-total-biaya');
    const avgBiayaEl = document.getElementById('hist-avg-biaya');
    const costPerKmEl = document.getElementById('hist-cost-per-km');
    const receiptCountEl = document.getElementById('hist-receipt-count');
    const lastOdoEl = document.getElementById('hist-last-odo');

    if (totalServisEl) totalServisEl.textContent = `${m.total_services || 0} Kali Servis`;
    if (totalBiayaEl) totalBiayaEl.textContent = `Rp ${(m.total_cost || 0).toLocaleString('id-ID')}`;
    if (avgBiayaEl) avgBiayaEl.textContent = `Rp ${(m.avg_cost || 0).toLocaleString('id-ID')}`;
    if (costPerKmEl) costPerKmEl.textContent = `Rp ${(m.cost_per_km || 0).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 2 })} / km`;
    if (receiptCountEl) receiptCountEl.textContent = `${records.filter(r => !!r.foto_nota).length} Terlampir`;
    if (lastOdoEl) lastOdoEl.textContent = `${Number(m.last_odo || 0).toLocaleString('id-ID')} km`;

    // Filter controls
    const compFilter = document.getElementById('hist-filter-component') ? document.getElementById('hist-filter-component').value : 'all';
    const searchVal = document.getElementById('hist-search-input') ? document.getElementById('hist-search-input').value.toLowerCase() : '';

    const filtered = records.filter(r => {
      if (compFilter !== 'all' && !r.nama_komponen.toLowerCase().includes(compFilter.toLowerCase())) {
        return false;
      }
      if (searchVal && !r.nama_komponen.toLowerCase().includes(searchVal) && !(r.bengkel || '').toLowerCase().includes(searchVal) && !(r.kode_part || '').toLowerCase().includes(searchVal)) {
        return false;
      }
      return true;
    });

    const tbody = document.getElementById('history-table-body');
    if (!tbody) return;

    if (filtered.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="8" class="p-space-xl text-center text-on-surface-variant font-body-md">
            Belum ada riwayat penggantian servis yang tercatat.
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = filtered.map(r => {
      const isCancelled = r.status === 'dibatalkan';
      const statusBadge = isCancelled
        ? `<span class="bg-surface-container-high text-on-surface-variant px-space-xs py-0.5 rounded font-mono text-label-sm font-semibold">Dibatalkan</span>`
        : `<span class="bg-secondary-container/40 text-on-secondary-container px-space-xs py-0.5 rounded font-label-sm text-label-sm font-semibold flex items-center gap-1 w-fit"><span class="material-symbols-outlined text-xs">verified</span> Aktif</span>`;

      // Cancel button
      const cancelBtn = r.can_cancel ? `
        <button onclick="App.cancelService(${r.id})" class="text-error hover:bg-error-container/30 px-2 py-1 rounded text-label-sm font-semibold flex items-center gap-1 transition-colors" title="Batalkan penggantian ini (berlaku dalam 24 jam)">
          <span class="material-symbols-outlined text-sm">undo</span>
          <span>Batalkan</span>
        </button>
      ` : '-';

      // Receipt Nota Thumbnail / Button
      const notaBtn = r.foto_nota ? `
        <button onclick="App.openReceiptModal('${r.foto_nota}', '${r.nama_komponen.replace(/'/g, "\\'")}', '${(r.bengkel || '').replace(/'/g, "\\'")}', '${r.tanggal_ganti}', ${r.biaya || 0})" class="flex items-center gap-1 px-2.5 py-1 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-xs font-semibold shadow-xs transition-colors" title="Lihat Foto Bukti Nota / Kuitansi">
          <span class="material-symbols-outlined text-sm">receipt_long</span>
          <span>Lihat Nota</span>
        </button>
      ` : `<span class="text-on-surface-variant text-xs italic">-</span>`;

      return `
        <tr class="border-b border-surface-container-low hover:bg-surface-container-low/50 transition-colors ${isCancelled ? 'opacity-60 bg-surface-container-lowest/40' : ''}">
          <td class="p-space-md font-mono text-body-sm font-semibold text-on-surface">${this.formatDate(r.tanggal_ganti)}</td>
          <td class="p-space-md">
            <div class="font-semibold text-on-surface">${r.nama_komponen}</div>
            <div class="font-mono text-body-sm text-on-surface-variant">${r.kode_part || '-'}</div>
          </td>
          <td class="p-space-md font-mono text-body-sm text-on-surface">${Number(r.odometer_saat_ganti).toLocaleString('id-ID')} km</td>
          <td class="p-space-md text-body-sm text-on-surface-variant">${r.bengkel || '-'}</td>
          <td class="p-space-md font-mono text-body-sm font-bold text-on-surface">Rp ${Number(r.biaya || 0).toLocaleString('id-ID')}</td>
          <td class="p-space-md">${notaBtn}</td>
          <td class="p-space-md">${statusBadge}</td>
          <td class="p-space-md text-right">${cancelBtn}</td>
        </tr>
      `;
    }).join('');
  },

  async cancelService(serviceId) {
    const confirmed = confirm('Batalkan penggantian ini? Titik awal (baseline) odometer dan tanggal komponen akan dikembalikan ke data sebelumnya.');
    if (!confirmed) return;

    const res = await this.api('cancel_service', 'POST', { service_id: serviceId });
    if (res.success) {
      this.showToast(res.message, 'undo');
      this.loadHistory();
      this.refreshData();
    } else {
      this.showToast(res.error || 'Gagal membatalkan servis', 'error');
    }
  },

  // Manual service modal
  openManualServiceModal() {
    const motor = this.state.motor;
    if (!motor) {
      this.showToast('Daftarkan motor terlebih dahulu', 'error');
      return;
    }
    document.getElementById('manual-serv-date').value = new Date().toISOString().split('T')[0];
    if (this.state.motor) {
      document.getElementById('manual-serv-odo').value = this.state.motor.odometer_saat_ini;
    }
    this.removeNotaPreview(null, 'manual-nota-file', 'manual-nota-preview', 'manual-nota-preview-container', 'manual-nota-placeholder', 'manual');

    const modal = document.getElementById('modal-manual-service');
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
  },

  closeManualServiceModal() {
    const modal = document.getElementById('modal-manual-service');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
    this.removeNotaPreview(null, 'manual-nota-file', 'manual-nota-preview', 'manual-nota-preview-container', 'manual-nota-placeholder', 'manual');
  },

  async submitManualService() {
    const motor = this.state.motor;
    if (!motor) return;

    const nama = document.getElementById('manual-serv-name').value;
    const kode = document.getElementById('manual-serv-part-code').value;
    const odo = parseInt(document.getElementById('manual-serv-odo').value, 10);
    const date = document.getElementById('manual-serv-date').value;
    const bengkel = document.getElementById('manual-serv-bengkel').value;
    const biaya = parseInt(document.getElementById('manual-serv-cost').value, 10) || 0;
    const catatan = document.getElementById('manual-serv-notes').value;
    const fotoNota = this.state.manualNotaBase64 || null;

    if (!nama || isNaN(odo) || odo <= 0) {
      this.showToast('Nama komponen dan odometer wajib diisi', 'error');
      return;
    }

    const res = await this.api('add_manual_service', 'POST', {
      user_motor_id: motor.id,
      nama_komponen: nama,
      kode_part: kode,
      odometer: odo,
      tanggal: date,
      bengkel,
      biaya,
      catatan,
      foto_nota: fotoNota,
    });

    if (res.success) {
      this.closeManualServiceModal();
      this.showToast(res.message, 'check_circle');
      this.loadHistory();
    } else {
      this.showToast(res.error || 'Gagal menyimpan servis manual', 'error');
    }
  },

  // ====================================================
  // RECEIPT NOTA PREVIEW & FILE HANDLERS
  // ====================================================
  handleNotaFileSelect(event, previewId, containerId, placeholderId, mode) {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
      this.showToast('Hanya berkas gambar (JPG, PNG) yang diperbolehkan', 'error');
      return;
    }

    if (file.size > 5 * 1024 * 1024) {
      this.showToast('Ukuran berkas maksimal 5 MB', 'error');
      return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
      const base64 = e.target.result;
      const previewImg = document.getElementById(previewId);
      const container = document.getElementById(containerId);
      const placeholder = document.getElementById(placeholderId);

      if (previewImg) previewImg.src = base64;
      if (container) container.classList.remove('hidden');
      if (placeholder) placeholder.classList.add('hidden');

      if (mode === 'reset') {
        this.state.resetNotaBase64 = base64;
      } else if (mode === 'manual') {
        this.state.manualNotaBase64 = base64;
      }
    };
    reader.readAsDataURL(file);
  },

  removeNotaPreview(event, inputId, previewId, containerId, placeholderId, mode) {
    if (event) {
      event.stopPropagation();
      event.preventDefault();
    }
    const input = document.getElementById(inputId);
    const previewImg = document.getElementById(previewId);
    const container = document.getElementById(containerId);
    const placeholder = document.getElementById(placeholderId);

    if (input) input.value = '';
    if (previewImg) previewImg.src = '';
    if (container) container.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');

    if (mode === 'reset') {
      this.state.resetNotaBase64 = null;
    } else if (mode === 'manual') {
      this.state.manualNotaBase64 = null;
    }
  },

  openReceiptModal(url, component, workshop, date, cost) {
    const modal = document.getElementById('modal-preview-nota');
    if (!modal) return;
    const img = document.getElementById('preview-nota-img');
    const title = document.getElementById('preview-nota-title');
    const subtitle = document.getElementById('preview-nota-subtitle');
    const costEl = document.getElementById('preview-nota-cost');
    const download = document.getElementById('preview-nota-download');

    if (img) img.src = url;
    if (title) title.textContent = component || 'Bukti Nota Servis';
    if (subtitle) subtitle.textContent = `${workshop || 'Bengkel'} • ${this.formatDate(date)}`;
    if (costEl) costEl.textContent = `Rp ${Number(cost || 0).toLocaleString('id-ID')}`;
    if (download) download.href = url;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  },

  closeReceiptModal() {
    const modal = document.getElementById('modal-preview-nota');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  },

  // ====================================================
  // BUKU SERVIS DIGITAL (PDF EXPORT, FR-050 - FR-053)
  // ====================================================
  async renderPdfSheet() {
    await this.loadHistory();
    const m = this.state.motor;
    const u = this.state.user;
    const records = (this.state.historyRecords || []).filter(r => r.status === 'aktif');

    // Document Meta
    const docIdEl = document.getElementById('pdf-doc-id');
    const docOwnerEl = document.getElementById('pdf-doc-owner');
    const docBikeEl = document.getElementById('pdf-doc-bike');
    const docPlateEl = document.getElementById('pdf-doc-plate');
    const docOdoEl = document.getElementById('pdf-doc-odo');
    const docDateEl = document.getElementById('pdf-doc-date');

    const docId = `MC-${new Date().getFullYear()}-${m ? String(m.id).padStart(4, '0') : '0000'}A`;
    if (docIdEl) docIdEl.textContent = docId;
    if (docOwnerEl) docOwnerEl.textContent = u ? u.nama : '-';
    if (docBikeEl) docBikeEl.textContent = m ? `${m.merek} ${m.tipe} ${m.varian ? `(${m.varian})` : ''}` : '-';
    if (docPlateEl) docPlateEl.textContent = m ? m.nomor_polisi : '-';
    if (docOdoEl) docOdoEl.textContent = m ? `${Number(m.odometer_saat_ini).toLocaleString('id-ID')} km` : '-';
    if (docDateEl) docDateEl.textContent = this.formatDate(new Date().toISOString());

    // Render Table in PDF Sheet
    const tableBody = document.getElementById('pdf-table-body');
    const emptyNotice = document.getElementById('pdf-empty-notice');

    if (records.length === 0) {
      if (tableBody) tableBody.innerHTML = '';
      if (emptyNotice) emptyNotice.classList.remove('hidden');
    } else {
      if (emptyNotice) emptyNotice.classList.add('hidden');
      if (tableBody) {
        tableBody.innerHTML = records.map((r, i) => `
          <tr class="border-b border-[#e4beba]/30">
            <td class="py-2.5 px-3 text-center font-mono text-xs">${i + 1}</td>
            <td class="py-2.5 px-3 font-mono text-xs">${this.formatDate(r.tanggal_ganti)}</td>
            <td class="py-2.5 px-3 font-semibold text-xs text-gray-900">${r.nama_komponen}</td>
            <td class="py-2.5 px-3 font-mono text-xs text-gray-600">${r.kode_part || '-'}</td>
            <td class="py-2.5 px-3 font-mono text-xs font-semibold text-right">${Number(r.odometer_saat_ganti).toLocaleString('id-ID')} km</td>
            <td class="py-2.5 px-3 text-xs text-gray-700">${r.bengkel || '-'}</td>
          </tr>
        `).join('');
      }
    }
  },

  downloadPdf() {
    const element = document.getElementById('pdf-document-sheet');
    if (!element) return;

    this.showToast('Menyusun berkas PDF resmi...', 'picture_as_pdf');

    const opt = {
      margin: [10, 10, 10, 10],
      filename: `Buku_Servis_MotoCare_${(this.state.motor && this.state.motor.nomor_polisi) ? this.state.motor.nomor_polisi.replace(/\s+/g, '_') : 'Motor'}.pdf`,
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2, useCORS: true },
      jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    if (window.html2pdf) {
      html2pdf().set(opt).from(element).save().then(() => {
        this.showToast('Buku Servis PDF berhasil diunduh!', 'check_circle');
      }).catch(err => {
        console.error('PDF Export Error:', err);
        window.print();
      });
    } else {
      window.print();
    }
  },

  zoomPdf(delta) {
    this.state.zoomLevel = Math.max(50, Math.min(150, this.state.zoomLevel + delta));
    const sheet = document.getElementById('pdf-document-sheet');
    const label = document.getElementById('pdf-zoom-text');
    if (sheet) sheet.style.transform = `scale(${this.state.zoomLevel / 100})`;
    if (label) label.textContent = `${this.state.zoomLevel}%`;
  },

  // ====================================================
  // MOTOR REGISTRATION (FR-010 - FR-014)
  // ====================================================
  async loadCatalog() {
    const res = await this.api('get_catalog');
    if (res.success) {
      this.state.catalog = res.catalog || [];
      this.populateCatalogSelects();
    }
  },

  escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, char => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
    })[char]);
  },

  populateCatalogSelects() {
    const brandSelect = document.getElementById('reg-brand-select');
    const typeSelect = document.getElementById('reg-type-select');
    if (!brandSelect || !typeSelect) return;

    // Get unique brands
    const brands = [...new Set(this.state.catalog.map(c => c.merek))];
    brandSelect.innerHTML = '<option value="">-- Pilih Merek Motor --</option>' + brands.map(b => {
      const safeBrand = this.escapeHtml(b);
      return `<option value="${safeBrand}">${safeBrand}</option>`;
    }).join('');

    brandSelect.onchange = () => {
      const selectedBrand = brandSelect.value;
      const filtered = this.state.catalog.filter(c => c.merek === selectedBrand);
      typeSelect.innerHTML = '<option value="">-- Pilih Tipe Motor --</option>' + filtered.map(t => {
        const label = `${this.escapeHtml(t.tipe)} ${t.varian ? `(${this.escapeHtml(t.varian)})` : ''}`;
        return `<option value="${Number(t.id)}">${label}</option>`;
      }).join('');
      typeSelect.disabled = false;
      this.renderBaselineInputs(null);
    };

    typeSelect.onchange = () => {
      const typeId = parseInt(typeSelect.value, 10);
      const selectedMotor = this.state.catalog.find(c => c.id === typeId);
      this.renderBaselineInputs(selectedMotor ? selectedMotor.parts : null);
    };
  },

  toggleCustomMotorForm() {
    const catalogFields = document.getElementById('reg-catalog-fields');
    const customFields = document.getElementById('reg-custom-motor-fields');
    const toggleButton = document.getElementById('reg-custom-mode-toggle');
    const catalogInputs = [
      document.getElementById('reg-brand-select'),
      document.getElementById('reg-type-select'),
    ];
    const customInputs = [
      document.getElementById('reg-custom-brand'),
      document.getElementById('reg-custom-type'),
      document.getElementById('reg-custom-variant'),
      document.getElementById('reg-custom-kind'),
    ];
    if (!catalogFields || !customFields) return;

    const useCustomMotor = customFields.classList.contains('hidden');
    catalogFields.classList.toggle('hidden', useCustomMotor);
    customFields.classList.toggle('hidden', !useCustomMotor);
    catalogInputs.forEach(input => {
      if (!input) return;
      input.required = !useCustomMotor;
      input.disabled = useCustomMotor;
    });
    const brandSelect = document.getElementById('reg-brand-select');
    const typeSelect = document.getElementById('reg-type-select');
    if (typeSelect) typeSelect.disabled = useCustomMotor || !brandSelect?.value;
    customInputs.forEach(input => {
      if (!input) return;
      input.required = useCustomMotor;
      input.disabled = !useCustomMotor;
    });
    if (toggleButton) {
      toggleButton.textContent = useCustomMotor
        ? 'Pilih motor dari katalog'
        : 'Motor tidak ada di katalog? Isi data sendiri';
    }

    const baselineContainer = document.getElementById('reg-custom-baselines-container');
    if (useCustomMotor && baselineContainer) {
      baselineContainer.innerHTML = '<p class="text-xs text-on-surface-variant">Komponen awal akan otomatis mengikuti jenis motor. Titik awal penggantian diatur ke odometer saat ini.</p>';
    } else if (!useCustomMotor) {
      this.renderBaselineInputs(null);
    }
  },

  renderBaselineInputs(parts) {
    const container = document.getElementById('reg-custom-baselines-container');
    if (!container) return;

    if (!parts || parts.length === 0) {
      container.innerHTML = '<p class="text-xs text-on-surface-variant">Pilih tipe motor untuk melihat suku cadang bawaan.</p>';
      return;
    }

    container.innerHTML = `
      <div class="text-xs font-semibold text-on-surface mb-2">Titik Awal Penggantian Terakhir Komponen (Opsional):</div>
      <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
        ${parts.map(p => `
          <div class="bg-surface-container-low p-2 rounded text-xs grid grid-cols-1 sm:grid-cols-3 gap-2 items-center">
            <span class="font-semibold text-on-surface">${this.escapeHtml(p.nama_komponen)}</span>
            <input type="number" data-part-id="${p.id}" class="custom-base-odo bg-surface-container-lowest p-1.5 rounded border border-[#e4beba]/60" placeholder="Odo ganti (km)">
            <input type="date" data-part-id="${p.id}" class="custom-base-date bg-surface-container-lowest p-1.5 rounded border border-[#e4beba]/60">
          </div>
        `).join('')}
      </div>
    `;
  },

  async submitRegisterMotor() {
    const typeSelect = document.getElementById('reg-type-select');
    const plateInput = document.getElementById('reg-plate-input');
    const odoInput = document.getElementById('reg-odo-input');
    const customFields = document.getElementById('reg-custom-motor-fields');
    const useCustomMotor = customFields && !customFields.classList.contains('hidden');

    const plate = plateInput.value.trim();
    const odo = parseInt(odoInput.value, 10);
    const payload = {
      nomor_polisi: plate,
      odometer: odo,
    };

    if (useCustomMotor) {
      const merek = document.getElementById('reg-custom-brand').value.trim();
      const tipe = document.getElementById('reg-custom-type').value.trim();
      const varian = document.getElementById('reg-custom-variant').value.trim();
      const jenisMotor = document.getElementById('reg-custom-kind').value;
      if (!merek || !tipe || !jenisMotor) {
        this.showToast('Lengkapi merek, tipe, dan jenis motor', 'error');
        return;
      }
      payload.custom_motor = { merek, tipe, varian, jenis_motor: jenisMotor };
    } else {
      const motorId = parseInt(typeSelect.value, 10);
      if (isNaN(motorId)) {
        this.showToast('Pilih merek dan tipe motor dari katalog', 'error');
        return;
      }
      payload.master_motor_id = motorId;
    }

    if (!plate || isNaN(odo) || odo < 0) {
      this.showToast('Harap lengkapi nomor polisi dan odometer saat ini', 'error');
      return;
    }

    // Collect custom baselines if any
    const customBaselines = {};
    document.querySelectorAll('.custom-base-odo').forEach(el => {
      const partId = el.getAttribute('data-part-id');
      const odoVal = el.value ? parseInt(el.value, 10) : null;
      const dateEl = document.querySelector(`.custom-base-date[data-part-id="${partId}"]`);
      const dateVal = dateEl ? dateEl.value : null;

      if (odoVal !== null || dateVal) {
        customBaselines[partId] = { odometer: odoVal, tanggal: dateVal };
      }
    });

    payload.custom_baselines = customBaselines;
    const res = await this.api('register_motor', 'POST', payload);

    if (res.success) {
      this.showToast(res.message, 'two_wheeler');
      await this.refreshData();
      this.switchTab('dashboard');
    } else {
      this.showToast(res.error || 'Pendaftaran motor gagal', 'error');
    }
  },

  // ====================================================
  // ADMIN DATA MASTER (FR-060 - FR-063)
  // ====================================================
  async loadAdminData() {
    if (!this.state.user || this.state.user.role !== 'admin') {
      this.showToast('Halaman ini khusus Administrator', 'error');
      this.switchTab('dashboard');
      return;
    }

    const res = await this.api('admin_get_master');
    if (res.success) {
      this.renderAdminMotors(res.motors || []);
      this.renderAdminParts(res.parts || [], res.motors || []);
    }
  },

  renderAdminMotors(motors) {
    const tbody = document.getElementById('admin-motor-table-body');
    if (!tbody) return;

    tbody.innerHTML = motors.map(m => `
      <tr class="border-b border-surface-container-low">
        <td class="p-space-sm font-mono text-xs">${m.id}</td>
        <td class="p-space-sm font-bold text-xs">${this.escapeHtml(m.merek)}</td>
        <td class="p-space-sm text-xs font-semibold">${this.escapeHtml(m.tipe)}</td>
        <td class="p-space-sm text-xs">${m.jenis_motor === 'bebek' ? 'Bebek' : 'Matic'}</td>
        <td class="p-space-sm text-xs text-on-surface-variant">${this.escapeHtml(m.varian || '-')}</td>
        <td class="p-space-sm font-mono text-xs">${m.used_count || 0} pengguna</td>
        <td class="p-space-sm">
          <span class="px-2 py-0.5 rounded text-[11px] font-semibold ${m.aktif ? 'bg-secondary-container/40 text-on-secondary-container' : 'bg-error-container text-on-error-container'}">
            ${m.aktif ? 'Aktif' : 'Nonaktif'}
          </span>
        </td>
        <td class="p-space-sm text-right space-x-1">
          <button onclick="App.toggleMotorStatus(${m.id}, ${m.aktif ? 0 : 1})" class="text-xs px-2 py-1 rounded bg-surface-container hover:bg-surface-container-high transition-colors">
            ${m.aktif ? 'Nonaktifkan' : 'Aktifkan'}
          </button>
        </td>
      </tr>
    `).join('');
  },

  renderAdminParts(parts, motors) {
    const tbody = document.getElementById('admin-part-table-body');
    const selectMotor = document.getElementById('admin-part-motor-select');
    if (!tbody) return;

    if (selectMotor) {
      selectMotor.innerHTML = motors.map(m => `<option value="${Number(m.id)}">${this.escapeHtml(m.merek)} ${this.escapeHtml(m.tipe)}</option>`).join('');
    }

    tbody.innerHTML = parts.map(p => `
      <tr class="border-b border-surface-container-low">
        <td class="p-space-sm font-mono text-xs">${p.id}</td>
        <td class="p-space-sm text-xs font-semibold">${this.escapeHtml(p.merek)} ${this.escapeHtml(p.tipe)}</td>
        <td class="p-space-sm text-xs font-bold">${this.escapeHtml(p.nama_komponen)}</td>
        <td class="p-space-sm font-mono text-xs">${p.interval_km ? Number(p.interval_km).toLocaleString('id-ID') + ' km' : '-'}</td>
        <td class="p-space-sm font-mono text-xs">${p.interval_bulan ? p.interval_bulan + ' bln' : '-'}</td>
        <td class="p-space-sm font-mono text-xs text-primary font-bold">${p.kode_part_rekomendasi || '-'}</td>
        <td class="p-space-sm">
          <span class="px-2 py-0.5 rounded text-[11px] font-semibold ${p.aktif ? 'bg-secondary-container/40 text-on-secondary-container' : 'bg-error-container text-on-error-container'}">
            ${p.aktif ? 'Aktif' : 'Nonaktif'}
          </span>
        </td>
        <td class="p-space-sm text-right">
          <button onclick="App.togglePartStatus(${p.id}, ${p.aktif ? 0 : 1})" class="text-xs px-2 py-1 rounded bg-surface-container hover:bg-surface-container-high transition-colors">
            ${p.aktif ? 'Nonaktifkan' : 'Aktifkan'}
          </button>
        </td>
      </tr>
    `).join('');
  },

  async toggleMotorStatus(id, status) {
    const res = await this.api('admin_toggle_motor', 'POST', { id, status });
    if (res.success) {
      this.showToast(res.message, 'check_circle');
      this.loadAdminData();
    }
  },

  async togglePartStatus(id, status) {
    const res = await this.api('admin_toggle_part', 'POST', { id, status });
    if (res.success) {
      this.showToast(res.message, 'check_circle');
      this.loadAdminData();
    }
  },


  // ====================================================
  // ADD MASTER MOTOR MODAL (ADMIN, FR-060)
  // ====================================================
  openAddMotorModal() {
    // Reset form fields
    const idEl = document.getElementById('admin-motor-id');
    const merekEl = document.getElementById('admin-motor-merek');
    const tipeEl = document.getElementById('admin-motor-tipe');
    const varianEl = document.getElementById('admin-motor-varian');
    const jenisEl = document.getElementById('admin-motor-jenis');
    const titleEl = document.getElementById('modal-motor-title');

    if (idEl) idEl.value = '0';
    if (merekEl) merekEl.value = 'Honda';
    if (tipeEl) tipeEl.value = '';
    if (varianEl) varianEl.value = '';
    if (jenisEl) jenisEl.value = 'matic';
    if (titleEl) titleEl.textContent = 'Tambah Tipe Motor Baru';

    // Reset brand pills: activate Honda by default
    this.setMotorBrandPreset('Honda');

    // Open modal
    const modal = document.getElementById('modal-add-master-motor');
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      // Focus first empty required field
      setTimeout(() => {
        const tipeInput = document.getElementById('admin-motor-tipe');
        if (tipeInput) tipeInput.focus();
      }, 150);
    }
  },

  closeAddMotorModal() {
    const modal = document.getElementById('modal-add-master-motor');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  },

  setMotorBrandPreset(brand) {
    // Fill text input
    const merekEl = document.getElementById('admin-motor-merek');
    if (merekEl) merekEl.value = brand;

    // Update pill active state
    document.querySelectorAll('.brand-pill').forEach(pill => {
      const isActive = pill.dataset.brand === brand;
      pill.className = [
        'brand-pill px-2 py-1.5 rounded-lg border text-xs font-semibold text-center transition-all',
        isActive
          ? 'bg-primary/10 border-primary text-primary'
          : 'bg-surface-container-low border-surface-container-high text-on-surface hover:bg-surface-container'
      ].join(' ');
    });
  },

  async submitAddMasterMotor() {
    const merek = (document.getElementById('admin-motor-merek')?.value || '').trim();
    const tipe  = (document.getElementById('admin-motor-tipe')?.value  || '').trim();
    const varian = (document.getElementById('admin-motor-varian')?.value || '').trim();
    const jenisMotor = document.getElementById('admin-motor-jenis')?.value || '';
    const id    = parseInt(document.getElementById('admin-motor-id')?.value || '0', 10);

    if (!merek || !tipe || !['matic', 'bebek'].includes(jenisMotor)) {
      this.showToast('Merek, Tipe, dan Jenis motor wajib dipilih', 'error');
      return;
    }

    const res = await this.api('admin_save_motor', 'POST', { id, merek, tipe, varian, jenis_motor: jenisMotor });
    if (res.success) {
      this.closeAddMotorModal();
      this.showToast(res.message, 'check_circle');
      this.loadAdminData();
    } else {
      this.showToast(res.error || 'Gagal menyimpan data motor', 'error');
    }
  },

  // Add new master part modal
  openAddPartModal() {
    const modal = document.getElementById('modal-add-master-part');
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
  },

  closeAddPartModal() {
    const modal = document.getElementById('modal-add-master-part');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  },

  async submitAddMasterPart() {
    const motorId = parseInt(document.getElementById('admin-part-motor-select').value, 10);
    const nama = document.getElementById('admin-part-nama').value.trim();
    const km = parseInt(document.getElementById('admin-part-km').value, 10) || null;
    const bln = parseInt(document.getElementById('admin-part-bulan').value, 10) || null;
    const kode = document.getElementById('admin-part-kode').value.trim();

    const res = await this.api('admin_save_part', 'POST', {
      master_motor_id: motorId,
      nama_komponen: nama,
      interval_km: km,
      interval_bulan: bln,
      kode_part_rekomendasi: kode,
    });

    if (res.success) {
      this.closeAddPartModal();
      this.showToast(res.message, 'check_circle');
      this.loadAdminData();
    } else {
      this.showToast(res.error, 'error');
    }
  },

  // ====================================================
  // AUTH & USER SWITCHER (FR-001 - FR-004)
  // ====================================================
  openUserSwitcher() {
    const modal = document.getElementById('modal-user-switcher');
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      this.switchAuthModalTab('demo');
    }
  },

  closeUserSwitcher() {
    const modal = document.getElementById('modal-user-switcher');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  },

  switchAuthModalTab(tab) {
    const tabs = ['demo', 'login', 'register'];
    tabs.forEach(t => {
      const btn = document.getElementById(`auth-tab-${t}`);
      const panel = document.getElementById(`auth-panel-${t}`);
      if (btn && panel) {
        if (t === tab) {
          btn.className = "flex-1 py-2.5 text-center border-b-2 border-primary text-primary font-bold transition-all";
          panel.classList.remove('hidden');
        } else {
          btn.className = "flex-1 py-2.5 text-center text-on-surface-variant hover:text-on-surface transition-all";
          panel.classList.add('hidden');
        }
      }
    });
  },

  async switchAccount(email) {
    const res = await this.api('switch_user', 'POST', { email });
    if (res.success) {
      this.closeUserSwitcher();
      this.state.user = res.user;
      this.updateUserUI();
      this.showToast(res.message, 'account_circle');
      await this.refreshData();
      this.switchTab('dashboard');
    } else {
      this.showToast(res.error, 'error');
    }
  },

  async submitLogin() {
    const email = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value;

    const res = await this.api('login', 'POST', { email, password });
    if (res.success) {
      this.closeUserSwitcher();
      this.state.user = res.user;
      this.updateUserUI();
      this.showToast(`Selamat datang, ${res.user.nama}!`, 'login');
      await this.refreshData();
      this.switchTab('dashboard');
    } else {
      this.showToast(res.error || 'Login gagal', 'error');
    }
  },

  async submitRegister() {
    const nama = document.getElementById('reg-user-nama').value.trim();
    const email = document.getElementById('reg-user-email').value.trim();
    const password = document.getElementById('reg-user-password').value;

    const res = await this.api('register', 'POST', { nama, email, password });
    if (res.success) {
      this.closeUserSwitcher();
      this.state.user = res.user;
      this.updateUserUI();
      this.showToast('Akun berhasil dibuat! Silakan daftarkan motor Anda.', 'check_circle');
      await this.refreshData();
      this.switchTab('daftar-motor');
    } else {
      this.showToast(res.error || 'Pendaftaran akun gagal', 'error');
    }
  },

  async logout() {
    const res = await this.api('logout', 'POST');
    this.closeUserSwitcher();
    this.showToast('Sesi telah diakhiri (Logout)', 'logout');
    // Switch to Dimas or show empty
    this.state.user = null;
    this.state.motor = null;
    this.state.components = [];
    this.openUserSwitcher();
  },

  // ====================================================
  // UTILITIES & EVENT BINDINGS
  // ====================================================
  formatDate(dateStr) {
    if (!dateStr) return '-';
    try {
      const d = new Date(dateStr);
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
      return `${String(d.getDate()).padStart(2, '0')} ${months[d.getMonth()]} ${d.getFullYear()}`;
    } catch (e) {
      return dateStr;
    }
  },

  showToast(msg, iconName = 'check_circle') {
    const toast = document.getElementById('toastNotification');
    const toastText = document.getElementById('toastText');
    const toastIcon = document.getElementById('toastIcon');

    if (!toast || !toastText) return;

    toastText.textContent = msg;
    if (toastIcon) toastIcon.textContent = iconName;

    toast.classList.remove('translate-y-20', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');

    setTimeout(() => {
      toast.classList.add('translate-y-20', 'opacity-0');
      toast.classList.remove('translate-y-0', 'opacity-100');
    }, 4000);
  },

  bindEvents() {
    // Intercept clicks on links with data-nav-tab for clean routing without full page reload
    document.addEventListener('click', (e) => {
      const navLink = e.target.closest('a[data-nav-tab]');
      if (navLink) {
        e.preventDefault();
        const tab = navLink.getAttribute('data-nav-tab');
        this.switchTab(tab, true);
      }
    });

    // Escape key closes modals
    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        this.closeResetModal();
        this.closeOdoModal();
        this.closeManualServiceModal();
        this.closeAddPartModal();
        this.closeAddMotorModal();
        this.closeUserSwitcher();
      }
    });

    // Odometer delta live calc
    const odoInput = document.getElementById('new-odo-input');
    if (odoInput) {
      odoInput.addEventListener('input', () => this.calcOdoDelta());
    }

    // History filter events
    const histCompFilter = document.getElementById('hist-filter-component');
    if (histCompFilter) {
      histCompFilter.addEventListener('change', () => this.renderHistory());
    }
    const histSearch = document.getElementById('hist-search-input');
    if (histSearch) {
      histSearch.addEventListener('input', () => this.renderHistory());
    }
  }
};

// Auto start application on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
  App.init();
});
