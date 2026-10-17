<?php
// MotoCare Digital - View: Pendaftaran Motor Baru (UC-02, FR-010 - FR-014)
?>

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
              Pilih tipe motor dari katalog master untuk memuat standar interval penggantian pabrikan secara otomatis.
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
              <p class="text-xs text-on-surface-variant mt-1">Rentang 0 - 999.999 km.</p>
            </div>

            <!-- Optional Initial Custom Baselines Accordion -->
            <details class="bg-surface-container-low p-space-md rounded-lg border border-[#e4beba]/40">
              <summary class="font-label-md text-label-md font-semibold text-on-surface cursor-pointer flex items-center justify-between">
                <span>Pengaturan Penggantian Awal (Opsional)</span>
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

