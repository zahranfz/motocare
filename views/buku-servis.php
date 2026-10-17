<?php

?>

    <!-- ====================================================== -->
    <!-- VIEW 4: BUKU SERVIS DIGITAL PDF                        -->
    <!-- ====================================================== -->
    <section id="view-buku-servis" class="tab-content w-full">
      
      <!-- Top Action Command Bar -->
      <div class="w-full bg-surface-container-low shadow-sm py-space-md border-b border-surface-container-high">
        <div class="max-w-[1320px] mx-auto px-margin md:px-margin-md lg:px-margin-lg flex flex-col lg:flex-row lg:items-center justify-between gap-space-md">
          <div class="flex flex-col">
            <div class="flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">
              <span class="inline-block w-2 h-2 rounded-full bg-secondary"></span>
              <span>Buku Servis Elektronik • Modul Ekspor Resmi</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight mt-space-xs">
              Buku Servis Digital (Digital Maintenance Logbook)
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl mt-0.5">
              Dokumen riwayat perawatan resmi untuk bukti jual beli &amp; rekam jejak performa motor berkala terverifikasi.
            </p>
          </div>

          <!-- Action Buttons (Single Consolidated Action) -->
          <div class="flex flex-wrap items-center gap-space-sm pt-space-xs lg:pt-0">
            <button onclick="App.downloadPdf()" class="flex items-center gap-space-xs bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg px-space-md py-space-sm rounded-lg shadow-sm transition-all active:translate-y-0.5 cursor-pointer" type="button">
              <span class="material-symbols-outlined text-xl">picture_as_pdf</span>
              <span>Unduh Buku Servis PDF</span>
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
                <div class="flex justify-between"><span class="text-on-surface-variant">Keterangan Dokumen:</span><span class="font-semibold text-primary">Pencatatan Mandiri</span></div>
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

                <!-- Empty state notice -->
                <div id="pdf-empty-notice" class="hidden py-8 text-center text-gray-500 text-xs bg-gray-50 border border-gray-200 rounded mt-2">
                  Belum ada riwayat penggantian suku cadang yang tercatat pada sepeda motor ini.
                </div>
              </div>

              <!-- Official Mandated Legal Disclaimer (FR-053 & R8) -->
              <div class="border-t-2 border-gray-200 pt-4 mt-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-[11px] text-gray-500">
                <div class="space-y-1 max-w-lg">
                  <div class="font-bold text-gray-700 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs text-primary">policy</span>
                    Klausul &amp; Batasan Tanggung Jawab Hukum:
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
