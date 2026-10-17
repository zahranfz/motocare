# MotoCare Digital 🛵
**Motorcycle Maintenance Tracker & Smart Assistant**
*Karya Tim Hore — Hackathon Inovasi Digital 2026*

---

## 📌 Ringkasan Eksekutif & Solusi

**MotoCare Digital** adalah aplikasi web modern berbasis telemetri yang dirancang untuk mengatasi 4 masalah utama pemilik sepeda motor di Indonesia:
1. **Lupa Jadwal Komponen:** Perhitungan otomatis sisa kilometer & hari dengan indikator visual **Hijau (Kondisi Baik)**, **Kuning (Perlu Perhatian)**, dan **Merah (Waktunya Ganti)**.
2. **Literasi Komponen Rendah:** Rekomendasi kode suku cadang pabrikan OEM asli (AHM / YIMM / SGO) langsung pada kartu komponen.
3. **Pencatatan Servis Manual:** Fitur *One-Click Reset* ("Sudah Diganti") otomatis menyetel ulang baseline dan mencatat riwayat pemeliharaan, serta menghasilkan **Buku Servis Digital PDF resmi**.
4. **Sulit Menemukan Bengkel:** Pencari bengkel interaktif (Leaflet.js + OpenStreetMap) dengan pembeda tegas antara **Bengkel Resmi (AHASS / Yamaha)** dan **Bengkel Umum**, lengkap dengan rute GPS dan kontak.

Desain antarmuka mengadopsi **Stitch Red & Beige Palette** (`#AF101A` Crimson Precision, `#016D38` Emerald Status, `#864700` Amber Advisory, dan `#FEF8F3` Sandstone Canvas) dengan tipografi **Space Grotesk** dan **Hanken Grotesk**.

---

## 🚀 Panduan Menjalankan Aplikasi

Aplikasi dirancang **zero-configuration**: database SQLite terinisialisasi secara otomatis lengkap dengan data *seed*, sehingga langsung dapat digunakan tanpa repot impor database terlebih dahulu!

### Opsi 1: Menjalankan dengan PHP Built-in Server (Rekomendasi Cepat)
Buka terminal PowerShell pada direktori proyek:
```powershell
C:\xampp\php\php.exe -S 127.0.0.1:8000
```
Buka peramban (browser) di:
👉 **`http://127.0.0.1:8000`**

### Opsi 2: Menjalankan via XAMPP Apache
1. Pastikan folder ini berada di: `c:\xampp\htdocs\motocare`
2. Buka XAMPP Control Panel dan nyalakan **Apache**.
3. Akses via browser di:
👉 **`http://localhost/motocare`**

*(Opsional untuk pengguna MySQL/phpMyAdmin)*: Skrip skema dan seed lengkap telah disediakan pada file [`database.sql`](file:///c:/xampp/htdocs/motocare/database.sql).

---

## 👥 Akun Demo & Akses Cepat (1-Click Switcher)

Untuk kenyamanan pengujian fitur dan penilaian dewan juri, telah disediakan tombol ganti akun instan pada pojok kanan atas (*User Chip*):

| Akun | Email | Password | Peran (Role) | Keterangan Data |
|---|---|---|---|---|
| **Dimas Prasetyo** | `dimas@motocare.id` | `password123` | **Pengguna** | Honda Vario 160 (B 4821 KRO), 11.200 km (1 Merah, 1 Kuning, 4 Hijau) |
| **Admin Tim Hore** | `admin@motocare.id` | `admin12345` | **Admin** | Akses Menu Pengelolaan Data Master (Motor & Suku Cadang) |
| **Budi Santoso (Ojol)** | `budi@motocare.id` | `password123` | **Pengguna** | Akun pengguna komuter jarak tinggi |

---

## 📋 Implementasi Functional Requirements (FR) Sesuai PRD

### Modul A — Akun & Akses (FR-001 – FR-004)
- Autentikasi sesi aman berbasis hash bcrypt (`password_hash`).
- Isolasi data pengguna: Pengguna hanya dapat mengakses dan memodifikasi data sepeda motor miliknya sendiri (BR-018).
- Role-based Access Control (RBAC): Endpoint data master dilindungi verifikasi `role === 'admin'`.

### Modul B — Sepeda Motor & Komponen (FR-010 – FR-015)
- Katalog bertingkat (Merek → Tipe) dari `master_motor`.
- Snapshot Pattern (BR-009): Saat motor didaftarkan, standar interval km, bulan, dan kode part disalin utuh ke `user_part_status` agar perubahan data master oleh admin di kemudian hari tidak merusak riwayat motor yang telah berjalan.
- Dukungan penyesuaian titik awal penggantian komponen kustom saat pendaftaran (FR-013).
- Pembatasan 1 motor aktif per akun untuk Fase 1 (FR-014).

### Modul C — Odometer & Status Komponen (FR-020 – FR-025)
- Perhitungan real-time setiap kali halaman dimuat (BR-007):
  - `Sisa km = interval_km - (odometer_sekarang - odometer_baseline)` (BR-001, mendukung nilai minus/terlambat)
  - `Sisa hari = (interval_bulan * 30) - selisih hari kalender WIB` (BR-002)
  - **Merah:** `sisa_km <= 0` ATAU `sisa_hari <= 0` (BR-003)
  - **Kuning:** `0 < sisa_km < 500` ATAU `0 < sisa_hari < 7` (BR-004)
  - **Hijau:** Di luar kondisi Merah dan Kuning (BR-005)
- Pengurutan prioritas otomatis: **Merah → Kuning → Hijau**, lalu berdasarkan sisa km terkecil (FR-023).
- Validasi Odometer (BR-014): Nilai tidak boleh lebih kecil dari odometer tercatat, dan lonjakan > 5.000 km memicu dialog konfirmasi keamanan.

### Modul D — Penggantian Komponen & Riwayat (FR-030 – FR-035)
- **One-Click Reset ("Sudah Diganti"):** Dialog konfirmasi dengan input odometer dan tanggal, secara atomik mereset baseline komponen dan mencatat riwayat servis (FR-031, FR-032).
- Pencegahan klik ganda (Debounce 10 detik per komponen, BR-011).
- **Pembatalan Penggantian (FR-035 / BR-016):** Penggantian terakhir suatu komponen dapat dibatalkan dalam waktu 24 jam; baseline dikembalikan ke titik sebelum servis dan baris riwayat ditandai `Dibatalkan` (tidak dihapus).

### Modul E — Pencari Bengkel Terdekat (FR-040 – FR-045)
- Peta interaktif Leaflet.js dengan penanda (marker) merah untuk **Bengkel Resmi (AHASS / Yamaha)** dan hijau untuk **Bengkel Umum & UMKM**.
- Pilihan radius pencarian: 3 km, 5 km (default), dan 10 km (BR-012).
- Dukungan Geolocation peramban + pemilihan titik manual di peta (FR-045).
- Tautan langsung rute navigasi Google Maps untuk setiap bengkel.

### Modul F — Buku Servis Digital PDF (FR-050 – FR-053)
- Ekspor PDF resmi berstandar A4 menggunakan pustaka `html2pdf.js` dan dukungan cetak browser (`window.print`).
- Memuat identitas lengkap pemilik, nomor polisi, spesifikasi motor, odometer ekspor, dan tabel riwayat kronologis.
- Mencantumkan klausul hukum wajib sesuai **FR-053 & R8**: *"Data dicatat secara mandiri oleh pemilik kendaraan dan tidak diverifikasi langsung oleh pihak bengkel resmi"*.

### Modul G — Administrasi Data Master (FR-060 – FR-063)
- CRUD Tipe Motor (`master_motor`) & Standar Komponen (`master_part`).
- Mekanisme Soft-Deactivate / Nonaktifkan (FR-062) untuk menjaga integritas data yang telah digunakan pengguna.

---

## 📂 Struktur Direktori Proyek

```
c:\xampp\htdocs\motocare\
├── api/
│   ├── db.php                     # Koneksi database PDO (SQLite otomatis / MySQL fallback)
│   └── index.php                  # Router REST API & Controller bisnis lengkap
├── assets/
│   ├── css/
│   │   └── custom.css             # Gaya kustom, pulse indicator, styling A4 print
│   └── js/
│       └── app.js                 # Logika front-end reaktif, Leaflet map, html2pdf
├── data/
│   └── motocare.db                # Database SQLite lokal terisolasi & ter-seed
├── database.sql                   # Skrip DDL & DML untuk MySQL / phpMyAdmin
├── index.php                      # Single-page Application utama terpadu
└── README.md                      # Dokumentasi teknis & panduan instalasi
```

---

*MotoCare Digital — Dikembangkan oleh Tim Hore untuk Hackathon Inovasi Mobilitas Cerdas 2026.*
