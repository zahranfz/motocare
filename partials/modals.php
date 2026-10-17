<?php
// MotoCare Digital - Partial: Modals & Dialogs (FR-020, FR-030, FR-061, FR-001, FR-002)
?>

  <!-- ======================================================== -->
  <!-- MODAL 1: ONE-CLICK RESET "SUDAH DIGANTI" (FR-030 - FR-033, BR-008) -->
  <!-- ======================================================== -->
  <div id="modal-reset-component" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-sm hidden items-center justify-center p-3 sm:p-4 md:p-6 overflow-y-auto">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full shadow-2xl border border-[#e4beba]/50 flex flex-col max-h-[min(92vh,700px)] my-auto overflow-hidden animate-in fade-in zoom-in-95 duration-200">
      
      <!-- Modal Header (Always sticky at top, close button always accessible) -->
      <div class="shrink-0 bg-surface-container-low px-space-md sm:px-space-lg py-3.5 flex items-center justify-between border-b border-surface-container-high">
        <div class="flex items-center gap-space-xs text-on-surface">
          <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
          <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Konfirmasi Penggantian Komponen</h3>
        </div>
        <button onclick="App.closeResetModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors" type="button" title="Tutup (Esc)">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <!-- Modal Body (Scrollable with custom scrollbar) -->
      <div class="p-space-md sm:p-space-lg space-y-space-md overflow-y-auto flex-1 custom-modal-scroll overscroll-contain">
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
            <input type="number" id="reset-odo-input" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-primary"/>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-xs font-semibold text-on-surface mb-1">Tanggal Penggantian</label>
              <input type="date" id="reset-date-input" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-primary"/>
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface mb-1">Estimasi Biaya (Rp)</label>
              <input type="number" id="reset-biaya-input" value="55000" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-primary"/>
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Bengkel Pelaksana</label>
            <input type="text" id="reset-bengkel-input" value="AHASS 07123 Daya Motor Depok" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs focus:outline-none focus:ring-2 focus:ring-primary"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Catatan Tambahan (Opsional)</label>
            <input type="text" id="reset-catatan-input" value="Penggantian rutin berkala" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs focus:outline-none focus:ring-2 focus:ring-primary"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1 flex items-center justify-between">
              <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-xs text-primary">receipt_long</span>
                Foto Nota / Kuitansi Servis (Opsional)
              </span>
              <span class="text-[10px] text-on-surface-variant font-normal">Maks 5 MB (JPG, PNG)</span>
            </label>
            <div class="border border-dashed border-[#e4beba] hover:border-primary rounded-lg p-2.5 bg-surface-container-low transition-colors text-center cursor-pointer" onclick="document.getElementById('reset-nota-file').click()">
              <input type="file" id="reset-nota-file" accept="image/*" class="hidden" onchange="App.handleNotaFileSelect(event, 'reset-nota-preview', 'reset-nota-preview-container', 'reset-nota-placeholder', 'reset')"/>
              <div id="reset-nota-placeholder" class="flex items-center justify-center gap-2 py-1">
                <span class="material-symbols-outlined text-on-surface-variant text-lg">add_a_photo</span>
                <span class="text-xs text-on-surface-variant font-medium">Klik untuk lampirkan foto nota fisik</span>
              </div>
              <div id="reset-nota-preview-container" class="hidden relative inline-block">
                <img id="reset-nota-preview" src="" alt="Preview Nota" class="max-h-24 rounded mx-auto shadow-xs border border-surface-container-high object-cover"/>
                <button type="button" onclick="App.removeNotaPreview(event, 'reset-nota-file', 'reset-nota-preview', 'reset-nota-preview-container', 'reset-nota-placeholder', 'reset')" class="absolute -top-1 -right-1 bg-error text-on-error rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-sm hover:opacity-90" title="Hapus foto">&times;</button>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-surface-container-high/60 p-space-sm rounded text-body-sm text-on-surface-variant flex items-start gap-space-xs">
          <span class="material-symbols-outlined text-primary text-base shrink-0 mt-0.5">info</span>
          <p class="text-[12px] leading-tight">
            Setelah dikonfirmasi, titik awal odometer dan tanggal komponen ini akan disetel ulang per hari ini. Catatan akan otomatis tersimpan dalam Buku Servis Digital.
          </p>
        </div>
      </div>

      <!-- Modal Footer (Always sticky at bottom, actions always accessible) -->
      <div class="shrink-0 bg-surface-container-low px-space-md sm:px-space-lg py-3 flex items-center justify-end gap-space-sm border-t border-surface-container-high">
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
  <div id="modal-update-odometer" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-sm hidden items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full shadow-2xl border border-[#e4beba]/50 flex flex-col max-h-[min(92vh,600px)] my-auto overflow-hidden animate-in fade-in zoom-in-95 duration-200">
      
      <div class="shrink-0 bg-surface-container-low px-space-md sm:px-space-lg py-3.5 flex items-center justify-between border-b border-surface-container-high">
        <div class="flex items-center gap-space-xs text-on-surface">
          <span class="material-symbols-outlined text-primary text-xl">speed</span>
          <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Perbarui Odometer Motor</h3>
        </div>
        <button onclick="App.closeOdoModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors" type="button" title="Tutup (Esc)">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div class="p-space-md sm:p-space-lg space-y-space-md overflow-y-auto flex-1 custom-modal-scroll overscroll-contain">
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

        <div class="bg-surface-container-low p-space-sm rounded-lg text-body-sm text-on-surface-variant space-y-1 border border-surface-container-high">
          <div class="flex justify-between text-[12px]">
            <span>Nilai saat ini:</span>
            <span id="modal-curr-odo-display" class="font-mono font-bold text-on-surface">11.200 km</span>
          </div>
          <div class="flex justify-between text-[12px]">
            <span>Kenaikan tercatat:</span>
            <span id="modal-odo-delta-display" class="font-mono font-bold text-secondary">+150 km</span>
          </div>
          <p class="text-[11px] text-on-surface-variant pt-1 border-t border-surface-container-high/60">
            *Catatan: Odometer bilangan bulat 0 - 999.999 km; penambahan lebih dari 5.000 km memicu konfirmasi keamanan lonjakan.
          </p>
        </div>
      </div>

      <div class="shrink-0 bg-surface-container-low px-space-md sm:px-space-lg py-3 flex items-center justify-end gap-space-sm border-t border-surface-container-high">
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
  <div id="modal-manual-service" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-sm hidden items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full shadow-2xl border border-[#e4beba]/50 flex flex-col max-h-[min(92vh,680px)] my-auto overflow-hidden animate-in fade-in zoom-in-95 duration-200">
      
      <div class="shrink-0 bg-surface-container-low px-space-md sm:px-space-lg py-3.5 flex items-center justify-between border-b border-surface-container-high">
        <div class="flex items-center gap-space-xs text-on-surface">
          <span class="material-symbols-outlined text-primary text-xl">build</span>
          <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Catat Servis Manual</h3>
        </div>
        <button onclick="App.closeManualServiceModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors" type="button" title="Tutup (Esc)">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div class="p-space-md sm:p-space-lg space-y-3 overflow-y-auto flex-1 custom-modal-scroll overscroll-contain">
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Nama Komponen / Pekerjaan</label>
          <input type="text" id="manual-serv-name" placeholder="Contoh: Kuras Radiator Coolant" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs focus:outline-none focus:ring-2 focus:ring-primary"/>
        </div>
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Kode Part (Bila ada)</label>
          <input type="text" id="manual-serv-part-code" placeholder="Contoh: 08CLA-M99-11LL1" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs uppercase focus:outline-none focus:ring-2 focus:ring-primary"/>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Odometer (km)</label>
            <input type="number" id="manual-serv-odo" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-primary"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Tanggal</label>
            <input type="date" id="manual-serv-date" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-primary"/>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Biaya (Rp)</label>
            <input type="number" id="manual-serv-cost" value="35000" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-primary"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Bengkel</label>
            <input type="text" id="manual-serv-bengkel" value="AHASS 07123 Daya Motor" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs focus:outline-none focus:ring-2 focus:ring-primary"/>
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Catatan</label>
          <input type="text" id="manual-serv-notes" placeholder="Catatan servis berkala" class="w-full bg-surface-container-low p-2 rounded border border-[#e4beba]/60 text-xs focus:outline-none focus:ring-2 focus:ring-primary"/>
        </div>
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1 flex items-center justify-between">
            <span class="flex items-center gap-1">
              <span class="material-symbols-outlined text-xs text-primary">receipt_long</span>
              Foto Nota / Kuitansi Servis (Opsional)
            </span>
            <span class="text-[10px] text-on-surface-variant font-normal">Maks 5 MB</span>
          </label>
          <div class="border border-dashed border-[#e4beba] hover:border-primary rounded-lg p-2.5 bg-surface-container-low transition-colors text-center cursor-pointer" onclick="document.getElementById('manual-nota-file').click()">
            <input type="file" id="manual-nota-file" accept="image/*" class="hidden" onchange="App.handleNotaFileSelect(event, 'manual-nota-preview', 'manual-nota-preview-container', 'manual-nota-placeholder', 'manual')"/>
            <div id="manual-nota-placeholder" class="flex items-center justify-center gap-2 py-1">
              <span class="material-symbols-outlined text-on-surface-variant text-lg">add_a_photo</span>
              <span class="text-xs text-on-surface-variant font-medium">Klik untuk lampirkan foto nota fisik</span>
            </div>
            <div id="manual-nota-preview-container" class="hidden relative inline-block">
              <img id="manual-nota-preview" src="" alt="Preview Nota" class="max-h-24 rounded mx-auto shadow-xs border border-surface-container-high object-cover"/>
              <button type="button" onclick="App.removeNotaPreview(event, 'manual-nota-file', 'manual-nota-preview', 'manual-nota-preview-container', 'manual-nota-placeholder', 'manual')" class="absolute -top-1 -right-1 bg-error text-on-error rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-sm hover:opacity-90" title="Hapus foto">&times;</button>
            </div>
          </div>
        </div>
      </div>

      <div class="shrink-0 bg-surface-container-low px-space-md sm:px-space-lg py-3 flex items-center justify-end gap-space-sm border-t border-surface-container-high">
        <button onclick="App.closeManualServiceModal()" class="px-space-md py-2.5 rounded-lg text-on-surface-variant hover:text-on-surface font-label-md text-label-md transition-colors" type="button">Batal</button>
        <button onclick="App.submitManualService()" class="bg-primary hover:bg-primary-container text-on-primary px-space-lg py-2.5 rounded-lg font-label-md text-label-md font-semibold shadow-sm transition-all">Simpan Catatan</button>
      </div>

    </div>
  </div>

  <!-- ======================================================== -->
  <!-- MODAL 4: TAMBAH MASTER PART (ADMIN, FR-061) -->
  <!-- ======================================================== -->
  <div id="modal-add-master-part" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-sm hidden items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full shadow-2xl border border-[#e4beba]/50 flex flex-col max-h-[min(92vh,680px)] my-auto overflow-hidden animate-in fade-in zoom-in-95 duration-200">
      
      <div class="shrink-0 bg-surface-container-low px-space-md sm:px-space-lg py-3.5 flex items-center justify-between border-b border-surface-container-high">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-lg">build</span>
          </div>
          <div>
            <h3 class="font-title-lg text-title-lg font-bold text-on-surface">Tambah Master Part Baru</h3>
            <p class="text-[11px] text-on-surface-variant font-medium">Standar Suku Cadang Pabrikan</p>
          </div>
        </div>
        <button onclick="App.closeAddPartModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors" type="button" title="Tutup (Esc)">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div class="p-space-md sm:p-space-lg space-y-3 overflow-y-auto flex-1 custom-modal-scroll overscroll-contain">
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Tipe Sepeda Motor</label>
          <select id="admin-part-motor-select" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs font-semibold text-on-surface focus:outline-none focus:ring-2 focus:ring-primary"></select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Nama Komponen</label>
          <input type="text" id="admin-part-nama" placeholder="Contoh: Busi NGK CPR9EA-9" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary"/>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Interval km</label>
            <input type="number" id="admin-part-km" placeholder="Contoh: 8000" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 font-mono text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary"/>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Interval Bulan</label>
            <input type="number" id="admin-part-bulan" placeholder="Contoh: 8" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 font-mono text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary"/>
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-on-surface mb-1">Kode Part Rekomendasi OEM</label>
          <input type="text" id="admin-part-kode" placeholder="Contoh: 31916-K97-T01" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 font-mono text-xs uppercase text-on-surface focus:outline-none focus:ring-2 focus:ring-primary"/>
        </div>
        <p class="text-[11px] text-on-surface-variant">
          *Catatan: Minimal salah satu interval (km atau bulan) wajib bernilai lebih dari 0.
        </p>
      </div>

      <div class="shrink-0 bg-surface-container-low px-space-md sm:px-space-lg py-3 flex items-center justify-end gap-space-sm border-t border-surface-container-high">
        <button onclick="App.closeAddPartModal()" class="px-space-md py-2.5 text-xs text-on-surface-variant font-semibold hover:text-on-surface">Batal</button>
        <button onclick="App.submitAddMasterPart()" class="bg-primary hover:bg-primary-container text-on-primary px-space-lg py-2.5 rounded-lg text-xs font-semibold shadow-sm transition-all">Simpan Master Part</button>
      </div>
    </div>
  </div>

  <!-- ======================================================== -->
  <!-- MODAL: TAMBAH MASTER TIPE MOTOR (ADMIN, FR-060) -->
  <!-- ======================================================== -->
  <div id="modal-add-master-motor" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-sm hidden items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full shadow-2xl border border-[#e4beba]/50 flex flex-col max-h-[min(92vh,680px)] my-auto overflow-hidden animate-in fade-in zoom-in-95 duration-200">
      
      <!-- Modal Header -->
      <div class="shrink-0 bg-surface-container-low px-space-md sm:px-space-lg py-3.5 flex items-center justify-between border-b border-surface-container-high">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-lg">two_wheeler</span>
          </div>
          <div>
            <h3 class="font-title-lg text-title-lg font-bold text-on-surface" id="modal-motor-title">Tambah Tipe Motor Baru</h3>
            <p class="text-[11px] text-on-surface-variant font-medium">Katalog Master Administrator</p>
          </div>
        </div>
        <button onclick="App.closeAddMotorModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors" type="button" title="Tutup (Esc)">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <!-- Modal Body -->
      <form onsubmit="event.preventDefault(); App.submitAddMasterMotor();" id="form-add-master-motor" class="flex flex-col flex-1 overflow-hidden">
        <input type="hidden" id="admin-motor-id" value="0"/>
        <div class="p-space-md sm:p-space-lg space-y-3.5 overflow-y-auto flex-1 custom-modal-scroll overscroll-contain">
          
          <!-- Merek Preset & Input -->
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1.5">Merek Sepeda Motor</label>
            <div class="grid grid-cols-4 gap-1.5 mb-2" id="motor-brand-pills">
              <button type="button" onclick="App.setMotorBrandPreset('Honda')" class="brand-pill px-2 py-1.5 rounded-lg border text-xs font-semibold text-center transition-all bg-primary/10 border-primary text-primary" data-brand="Honda">Honda</button>
              <button type="button" onclick="App.setMotorBrandPreset('Yamaha')" class="brand-pill px-2 py-1.5 rounded-lg border text-xs font-semibold text-center transition-all bg-surface-container-low border-surface-container-high text-on-surface hover:bg-surface-container" data-brand="Yamaha">Yamaha</button>
              <button type="button" onclick="App.setMotorBrandPreset('Suzuki')" class="brand-pill px-2 py-1.5 rounded-lg border text-xs font-semibold text-center transition-all bg-surface-container-low border-surface-container-high text-on-surface hover:bg-surface-container" data-brand="Suzuki">Suzuki</button>
              <button type="button" onclick="App.setMotorBrandPreset('Kawasaki')" class="brand-pill px-2 py-1.5 rounded-lg border text-xs font-semibold text-center transition-all bg-surface-container-low border-surface-container-high text-on-surface hover:bg-surface-container" data-brand="Kawasaki">Kawasaki</button>
            </div>
            <div class="relative">
              <input type="text" id="admin-motor-merek" value="Honda" placeholder="Ketik merek (contoh: Honda, Yamaha, Vespa)" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs font-semibold text-on-surface focus:outline-none focus:ring-2 focus:ring-primary pl-9" required/>
              <span class="material-symbols-outlined text-primary text-base absolute left-2.5 top-1/2 -translate-y-1/2">sell</span>
            </div>
          </div>

          <!-- Tipe Motor -->
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Tipe / Model Motor</label>
            <div class="relative">
              <input type="text" id="admin-motor-tipe" placeholder="Contoh: Stylo 160, Beat Street, Vario 160" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary pl-9 font-medium" required/>
              <span class="material-symbols-outlined text-primary text-base absolute left-2.5 top-1/2 -translate-y-1/2">sports_motorsports</span>
            </div>
            <p class="text-[11px] text-on-surface-variant mt-1">Nama model resmi sepeda motor.</p>
          </div>

          <!-- Varian / Tahun -->
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Varian &amp; Tahun Produksi (Opsional)</label>
            <div class="relative">
              <input type="text" id="admin-motor-varian" placeholder="Contoh: 2024 CBS-ISS / Connected ABS" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary pl-9 font-medium"/>
              <span class="material-symbols-outlined text-primary text-base absolute left-2.5 top-1/2 -translate-y-1/2">calendar_today</span>
            </div>
            <p class="text-[11px] text-on-surface-variant mt-1">Spesifikasi varian pengereman, transmisi, atau tahun produksi.</p>
          </div>

          <!-- Info Box -->
          <div class="bg-surface-container-high/60 p-space-sm rounded-lg text-body-sm text-on-surface-variant flex items-start gap-2 border border-surface-container-high">
            <span class="material-symbols-outlined text-primary text-base shrink-0 mt-0.5">info</span>
            <p class="text-[12px] leading-tight">
              Tipe motor yang baru disimpan akan langsung aktif dan muncul pada pilihan saat pengguna mendaftarkan motor baru atau menambahkan komponen master suku cadang.
            </p>
          </div>

        </div>

        <!-- Modal Footer -->
        <div class="shrink-0 bg-surface-container-low px-space-md sm:px-space-lg py-3 flex items-center justify-end gap-space-sm border-t border-surface-container-high">
          <button onclick="App.closeAddMotorModal()" class="px-space-md py-2.5 rounded-lg text-on-surface-variant hover:text-on-surface font-label-md text-label-md transition-colors" type="button">
            Batal
          </button>
          <button type="submit" class="flex items-center gap-space-xs bg-primary hover:bg-primary-container text-on-primary px-space-lg py-2.5 rounded-lg shadow-sm font-label-md text-label-md font-semibold transition-all">
            <span class="material-symbols-outlined text-base">save</span>
            <span>Simpan Tipe Motor</span>
          </button>
        </div>
      </form>

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
          Beralih peran atau akun instan antara Pengguna dan Administrator:
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
            <input type="password" id="reg-user-password" minlength="8" placeholder="Minimal 8 karakter" class="w-full bg-surface-container-low p-2.5 rounded-lg border border-[#e4beba]/60 text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary" required/>
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
  <!-- MODAL: PREVIEW BUKTI NOTA / KUITANSI SERVIS -->
  <!-- ======================================================== -->
  <div id="modal-preview-nota" class="fixed inset-0 z-50 bg-inverse-surface/70 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-[#e4beba]/60 flex flex-col max-h-[90vh]">
      <div class="bg-surface-container-low px-5 py-3.5 flex items-center justify-between border-b border-surface-container-high">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-primary text-xl">receipt_long</span>
          <div>
            <h3 class="font-title-md text-sm font-bold text-on-surface" id="preview-nota-title">Bukti Nota Servis</h3>
            <p class="text-[11px] text-on-surface-variant font-mono" id="preview-nota-subtitle">-</p>
          </div>
        </div>
        <button onclick="App.closeReceiptModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container" type="button">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div class="p-4 overflow-y-auto flex-1 flex flex-col items-center justify-center bg-black/5 min-h-[220px]">
        <img id="preview-nota-img" src="" alt="Bukti Kuitansi" class="max-h-[55vh] max-w-full rounded-lg shadow-md border border-surface-container-high object-contain"/>
      </div>

      <div class="bg-surface-container-low px-5 py-3 flex items-center justify-between border-t border-surface-container-high">
        <div class="text-xs">
          <span class="text-on-surface-variant">Total Biaya: </span>
          <strong id="preview-nota-cost" class="font-mono text-primary font-bold">Rp 0</strong>
        </div>
        <div class="flex items-center gap-2">
          <a id="preview-nota-download" href="#" target="_blank" download class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">open_in_new</span>
            <span>Buka Penuh</span>
          </a>
          <button onclick="App.closeReceiptModal()" class="px-3 py-1.5 rounded-lg bg-primary hover:bg-primary-container text-on-primary text-xs font-semibold">Tutup</button>
        </div>
      </div>
    </div>
  </div>

