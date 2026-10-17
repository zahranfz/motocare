<?php
// MotoCare Digital - View: Admin Data Master (UC-07, FR-060 - FR-063)
?>

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
              Kelola katalog tipe motor dan standar suku cadang pabrikan. Perubahan master tidak menimpa status motor terdaftar pengguna.
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
            <span>Katalog Master Sepeda Motor</span>
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
            <span>Standar Komponen &amp; Suku Cadang</span>
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

