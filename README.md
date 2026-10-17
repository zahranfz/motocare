# MotoCare Digital
### Motorcycle Maintenance Tracker & Smart Assistant
*Platform Pemantauan Perawatan Sepeda Motor Berbasis Telemetri Real-Time & Asisten Cerdas*  
**Finalist Informatics Championship x Catalyst Hackathon 2026**

---

### 📑 Daftar Isi (Sesuai Standar SOP Hackathon):
1. [Nama Tim dan Daftar Anggota](#1-nama-tim-dan-daftar-anggota)
2. [Deskripsi Permasalahan](#2-deskripsi-permasalahan)
3. [Solusi yang Dikembangkan](#3-solusi-yang-dikembangkan)
4. [Teknologi yang Digunakan](#4-teknologi-yang-digunakan)
5. [Petunjuk Instalasi dan Cara Menjalankan Aplikasi](#5-petunjuk-instalasi-dan-cara-menjalankan-aplikasi)

---

## 1. Nama Tim dan Daftar Anggota

* **Nama Tim:** Tim Hore
* **Daftar Anggota:**

| No | Nama Lengkap | Akun GitHub | Email | Peran & Tanggung Jawab |
|:---:|---|---|---|---|
| **1** | **Zahran Febrian Nugraha** | [@zahranfz](https://github.com/zahranfz) | `zahranfebriannugraha@gmail.com` | **Lead Developer & Full-Stack Architect** (Sistem Telemetri Odometer, REST API, Database SQLite/MySQL, Integrasi PWA) |
| **2** | **Bagas** | [@bagasrajin](https://github.com/bagasrajin) | `choplakgangster2425@gmail.com` | **Backend & Data Integration Engineer** (Logika Penggantian Komponen, Validasi Odometer, API Servis & Cost Tracker) |
| **3** | **Fatur Rohman Cesar** | [@faturcesar](https://github.com/faturcesar) | `faturrohmancp@gmail.com` | **Frontend Developer & UI/UX Specialist** (Pemisahan Tampilan Modular, Desain Responsif Tailwind, Buku Servis Digital PDF) |
| **4** | **Sofian Abdi Arrahman** | [@SofianProject](https://github.com/SofianProject) | `sofianabdiarrahman@gmail.com` | **Frontend & Mobile Experience Engineer** (Integrasi Peta Leaflet.js, WhatsApp Automation, PWA Service Worker & Pengujian) |

---

## 2. Deskripsi Permasalahan

Sepeda motor merupakan moda transportasi utama bagi lebih dari 120 juta masyarakat Indonesia, mulai dari komuter harian, kurir logistik, hingga pengemudi ojek online. Kendati demikian, efisiensi dan keamanan berkendara sering kali terancam oleh berbagai permasalahan perawatan kendaraan:

1. **Lupa Jadwal dan Keterlambatan Servis Komponen Kritis:**
   * Pengendara umumnya hanya mengandalkan ingatan atau estimasi kasar untuk jadwal servis. Keterlambatan penggantian oli mesin atau oli transmisi (gardan) berujung pada kerusakan parah silinder/piston dan turun mesin berbiaya jutaan rupiah. Begitu pula ausnya kampas rem dan V-belt/rantai yang berisiko fatal terhadap keselamatan di jalan.
2. **Rendahnya Literasi Standar Pabrikan & Keraguan Keaslian Suku Cadang (OEM):**
   * Sebagian besar pemilik kendaraan tidak memahami kode suku cadang resmi pabrikan (seperti nomor part resmi AHM, YIMM, atau SGO) dan interval jarak/waktu servis yang dianjurkan buku manual. Akibatnya, pengguna rentan membeli komponen palsu (KW) atau mengganti suku cadang prematur karena rekomendasi bengkel nakal.
3. **Pencatatan Servis Manual yang Tercecer, Hilang, dan Tanpa Transparansi Biaya:**
   * Buku servis fisik bawaan diler rentan hilang, rusak, atau tertinggal di rumah. Pengguna kehilangan rekam jejak riwayat servis (*service log*), tidak memiliki pencatatan pengeluaran finansial perawatan kendaraan (*cost per km*), serta kehilangan bukti kredibel saat hendak menjual motor bekas sehingga nilai jual kembali (*resale value*) merosot drastis.
4. **Kesulitan Menemukan dan Membedakan Bengkel Terdekat:**
   * Saat motor mengalami kendala di perjalanan, pengendara kesulitan mencari bengkel di sekitar lokasinya, tidak dapat membedakan secara instan antara **Bengkel Resmi (AHASS/Yamaha)** dengan **Bengkel Umum/UMKM**, serta minim akses kontak langsung untuk reservasi tanpa antre.

---

## 3. Solusi yang Dikembangkan

**MotoCare Digital** hadir sebagai solusi berbasis *smart assistant & progressive web telemetry* yang mengubah cara pemilik motor memantau, merawat, dan mengelola biaya kendaraan mereka secara otomatis, terukur, dan transparan.

### A. Gambaran Solusi dan Cara Kerjanya

1. **Pendaftaran Kendaraan & Garasi Multi-Motor (Multi-Motor Garage):**
   * Pengguna dapat mendaftarkan sepeda motor dari katalog master pabrikan (Matic & Bebek) maupun motor kustom.
   * **Snapshot Pattern (BR-009):** Saat motor didaftarkan, standar interval kilometer, interval bulan, dan rekomendasi kode suku cadang pabrikan disalin utuh ke profil motor aktif pengguna sehingga terlindungi dari manipulasi data master di kemudian hari.
   * Mendukung lebih dari 1 motor dalam 1 akun dengan *quick-switch dropdown* aktif di navigasi atas.

2. **Perhitungan Telemetri Real-Time & Sistem Triage Status (BR-001 – BR-007):**
   * Setiap kali odometer diperbarui, sistem mengalkulasi status kesehatan setiap komponen secara otomatis:
     $$\text{Sisa km} = \text{interval\_km} - (\text{odometer\_sekarang} - \text{odometer\_baseline})$$
     $$\text{Sisa hari} = (\text{interval\_bulan} \times 30) - \text{selisih hari kalender (WIB)}$$
   * **🔴 Merah (Waktunya Ganti):** `sisa_km <= 0` ATAU `sisa_hari <= 0` (Kondisi darurat, pemicu peringatan prioritas utama).
   * **🟡 Kuning (Perlu Perhatian):** `0 < sisa_km < 500` ATAU `0 < sisa_hari < 7` (Tahap antisipasi persiapan servis).
   * **🟢 Hijau (Kondisi Baik):** Komponen berada dalam ambang batas aman.
   * **Prioritas Tampilan Cerdas (FR-023):** Kartu komponen diurutkan otomatis: **Merah → Kuning → Hijau**, lalu berdasarkan sisa km terkecil.

3. **One-Click Maintenance Reset & Pembatalan 24 Jam (FR-030 – FR-035):**
   * Fitur **"Sudah Diganti"** mencatat riwayat pemeliharaan secara atomik, menyetel ulang baseline kilometer/tanggal komponen, mencatat biaya servis, serta mengizinkan unggah foto struk/nota pembayaran.
   * Dilengkapi pencegahan klik ganda (*Debounce* 10 detik, BR-011) serta mekanisme **Pembatalan Servis dalam 24 Jam** (BR-016) untuk mengoreksi kesalahan tanpa menghapus jejak audit log.

4. **Buku Servis Digital & Ekspor PDF Resmi Berstandar A4 (FR-050 – FR-053):**
   * Menghasilkan buku servis digital interaktif dengan identitas lengkap kendaraan (pemilik, nopol, odometer terkini) dan tabel riwayat pemeliharaan.
   * **One-Click Export PDF:** Menggunakan `html2pdf.js` dengan styling cetak CSS presisi A4 serta mencantumkan klausul hukum resmi kepemilikan data (FR-053).

5. **Pencari Bengkel Terdekat Interaktif (FR-040 – FR-045):**
   * Peta berbasis **Leaflet.js** dan **OpenStreetMap** dengan radius dinamis (3 km, 5 km, 10 km).
   * Pembeda visual tegas: Penanda Merah untuk **Bengkel Resmi (AHASS / Yamaha)** dan Hijau untuk **Bengkel Umum/UMKM**, lengkap dengan tombol navigasi rute Google Maps dan chat reservasi WhatsApp.

6. **Integrasi Cerdas WhatsApp (Smart Reminder & Direct Booking):**
   * **Kirim Pengingat ke WA:** Menghasilkan template pesan WhatsApp terstruktur yang merangkum komponen kritis (Merah/Kuning), rekomendasi kode part OEM, odometer motor, serta saran bengkel terdekat.
   * **Direct Workshop Booking:** Menghubungkan pengguna langsung ke kontak mekanik bengkel dengan draf pemesanan jadwal servis otomatis.

7. **Finansial Telemetri & Arsip Nota Digital (Cost Tracker):**
   * Menghitung akumulasi total biaya perawatan, rata-rata biaya per servis, rasio biaya per kilometer (*Cost per km*), serta galeri struk digital tersimpan (`uploads/nota/`).

8. **Progressive Web App (PWA) & Offline Resilience:**
   * Aplikasi dapat dipasang langsung (*Install App / Add to Home Screen*) di perangkat Android, iOS, maupun Desktop via `manifest.json`.
   * Layanan *Service Worker* (`sw.js`) dengan strategi caching pintar untuk memastikan akses riwayat dan navigasi tetap lancar meski di area minim sinyal.

---

## 4. Teknologi yang Digunakan

Aplikasi MotoCare Digital dibangun dengan arsitektur modern berorientasi performa tinggi, efisiensi sumber daya, dan kemudahan deployment:

### A. Bahasa Pemrograman & Backend
* **PHP 8.x (Native):** Engine backend berbasis REST API terstruktur (`api/index.php`), tanpa framework berat sehingga eksekusi sangat cepat, latensi rendah, dan ramah sumber daya server.
* **PDO (PHP Data Objects):** Abstraksi database aman dengan prepared statements untuk mencegah ancaman SQL Injection.
* **JavaScript (ES6+ Native):** Logika antarmuka reaktif berbasis Single Page Architecture tanpa *virtual DOM overhead*, memanfaatkan modul terpisah (`assets/js/app.js`).
* **HTML5 & Modern CSS3:** Markup semantik terstruktur dan styling presisi standar W3C.

### B. Framework & Pustaka Frontend
* **Tailwind CSS (v3 via CDN):** Desain sistem berbasis utilitas yang diintegrasikan dengan tema warna kustom **Stitch Red & Beige Palette** (`#AF101A` Crimson Precision, `#016D38` Emerald Status, `#864700` Amber Advisory, dan `#FEF8F3` Sandstone Canvas).
* **Leaflet.js (v1.9.4):** Library peta interaktif open-source berbasis JavaScript untuk visualisasi lokasi bengkel dan geolokasi pengguna.
* **OpenStreetMap (OSM) Tiles:** Penyedia layer peta global gratis tanpa batasan API key berbayar.
* **html2pdf.js (v0.10.1):** Pustaka konversi dokumen HTML ke PDF secara instan di sisi klien (*client-side rendering*).
* **Google Fonts (Space Grotesk & Hanken Grotesk):** Tipografi modern yang menjamin keterbacaan tinggi pada berbagai ukuran layar.
* **Material Symbols Outlined:** Ikonografi Google standar untuk representasi aksi dan navigasi yang intuitif.

### C. Database & Penyimpanan
* **SQLite 3 (`data/motocare.db`):** Database lokal berkecepatan tinggi, nir-konfigurasi (*zero-configuration*), ter-seed otomatis saat inisialisasi aplikasi.
* **MySQL / MariaDB (`database.sql`):** Skrip skema relasional lengkap (DDL & DML) untuk dukungan deployment skala enterprise.

### D. Layanan Web & Fitur Platform
* **W3C Web App Manifest (`manifest.json`) & Service Worker (`sw.js`):** Standar Progressive Web App (PWA) untuk instalasi native dan caching aset offline.
* **HTML5 Geolocation API:** Deteksi koordinat GPS pengguna secara *real-time*.
* **WhatsApp Click-to-Chat Protocol:** Integrasi komunikasi pesan pintar antar-pengguna dan pemilik bengkel.

### E. Struktur Direktori Proyek
```
motocare/
├── api/
│   ├── db.php                     # Koneksi database PDO (Auto SQLite & fallback MySQL)
│   └── index.php                  # Router REST API & Controller bisnis lengkap
├── assets/
│   ├── css/
│   │   └── custom.css             # Desain custom, pulse status, styling cetak A4
│   ├── icons/                     # Aset ikon PWA (icon-192.png, icon-512.png)
│   └── js/
│       └── app.js                 # Front-end state reaktif, Leaflet map, html2pdf
├── data/
│   └── motocare.db                # Database SQLite terisi data seed (Auto-generated)
├── partials/                      # Komponen antarmuka modular (header, navbar, modals)
│   ├── header.php
│   ├── modals.php
│   └── navbar.php
├── views/                         # Halaman antarmuka aplikasi
│   ├── admin.php                  # Panel Admin Master Motor & Suku Cadang
│   ├── bengkel.php                # Pencari Bengkel Interaktif & Navigasi
│   ├── buku-servis.php            # Buku Servis Digital & Ekspor PDF
│   ├── dashboard.php              # Dasbor Telemetri Komponen & Odometer
│   └── riwayat.php                # Riwayat Servis, Cost Tracker & Struk Nota
├── database.sql                   # Skrip DDL/DML untuk pengguna MySQL / phpMyAdmin
├── index.php                      # File utama entry point Single Page Application
├── manifest.json                  # Konfigurasi PWA Web App Manifest
├── sw.js                          # Service Worker PWA (Offline cache & sync)
└── README.md                      # Dokumentasi resmi proyek
```

---

## 5. Petunjuk Instalasi dan Cara Menjalankan Aplikasi

Aplikasi dirancang dengan prinsip **Zero Configuration**. Database lokal SQLite terinisialisasi secara otomatis lengkap dengan data awal (*seed*), sehingga aplikasi dapat langsung diuji tanpa langkah rumit.

### A. Prasyarat Sistem (Prerequisites)
Pastikan komputer Anda telah terpasang:
* **Git** ([Download Git](https://git-scm.com/))
* **PHP 8.0 atau lebih baru** (Ekstensi `pdo_sqlite` aktif secara bawaan pada PHP standar)
* Peramban Web Modern (*Google Chrome, Microsoft Edge, Brave, atau Mozilla Firefox*)
* *(Opsional)* **XAMPP / Laragon** jika ingin menjalankan via Apache web server

---

### B. Langkah 1: Clone Repository GitHub
Buka terminal (Command Prompt / PowerShell / Git Bash), lalu jalankan:
```bash
git clone https://github.com/zahranfz/motocare.git
cd motocare
```

---

### C. Langkah 2: Menjalankan Aplikasi

Pilih salah satu dari 2 metode mudah berikut:

#### Opsi 1 — Menggunakan PHP Built-in Server (Rekomendasi Cepat & Paling Mudah)
Jalankan server bawaan PHP langsung dari dalam direktori proyek:
```bash
php -S 127.0.0.1:8000
```
*(Bagi pengguna Windows dengan XAMPP terinstal, jika `php` belum masuk System PATH, gunakan perintah):*
```powershell
C:\xampp\php\php.exe -S 127.0.0.1:8000
```
Setelah server berjalan, buka browser dan kunjungi:  
👉 **`http://127.0.0.1:8000`**

---

#### Opsi 2 — Menggunakan Apache pada XAMPP
1. Pastikan folder repository berada di direktori `htdocs`:
   ```
   C:\xampp\htdocs\motocare
   ```
2. Buka aplikasi **XAMPP Control Panel** dan klik **Start** pada modul **Apache**.
3. Buka browser dan akses tautan:  
   👉 **`http://localhost/motocare`**

---

### D. Langkah 3: Akun Demo & Akses Pengujian Cepat (1-Click Switcher)

Untuk kenyamanan pengujian fitur oleh dewan juri, telah disediakan tombol ganti akun instan pada pojok kanan atas layar (*User Chip*):

| Akun / Profil | Email | Password | Role / Hak Akses | Skenario Data Pengujian |
|---|---|---|:---:|---|
| **Dimas Prasetyo** | `dimas@motocare.id` | `password123` | **Pengguna** | Honda Vario 160 (B 4821 KRO), 11.200 km.<br>Memiliki **1 Komponen Merah** (Oli Mesin), **1 Kuning** (Busi), dan **4 Hijau**. Ideal untuk uji triage telemetri, reset penggantian, ekspor PDF, dan kirim pengingat WhatsApp. |
| **Admin Tim Hore** | `admin@motocare.id` | `admin12345` | **Admin** | Memiliki akses ke menu navigasi **Admin Panel** untuk menambah/mengedit master motor dan katalog standar komponen suku cadang pabrikan. |
| **Budi Santoso (Ojol)** | `budi@motocare.id` | `password123` | **Pengguna** | Profil pengguna komuter jarak jauh / pengemudi ojol. Ideal untuk pengujian pendaftaran motor baru dan pencatatan servis. |

---

### E. Langkah 4: Opsi Database MySQL / phpMyAdmin (Opsional)
Secara bawaan, aplikasi langsung menggunakan SQLite di `data/motocare.db` tanpa perlu konfigurasi tambahan. Namun, jika dewan juri ingin menguji dengan database MySQL:
1. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Buat database baru dengan nama `motocare`.
3. Impor berkas [`database.sql`](database.sql) ke dalam database tersebut.
4. Buka [`api/db.php`](api/db.php) dan sesuaikan kredensial koneksi MySQL (default: `root` tanpa password).

---

*MotoCare Digital — Dikembangkan dengan bangga oleh Tim Hore pada Final Informatics Championship x Catalyst Hackathon 2026.*
