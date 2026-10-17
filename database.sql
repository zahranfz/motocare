-- ==========================================================
-- MotoCare Digital - Database Schema & Seeds (MySQL/MariaDB)
-- PRD Section 10: Model Data & Status
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `motocare` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `motocare`;

-- 1. Tabel users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('pengguna', 'admin') NOT NULL DEFAULT 'pengguna',
  `created_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel master_motor
CREATE TABLE IF NOT EXISTS `master_motor` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `merek` VARCHAR(50) NOT NULL,
  `tipe` VARCHAR(100) NOT NULL,
  `varian` VARCHAR(100) DEFAULT NULL,
  `aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `jenis_motor` VARCHAR(10) NOT NULL DEFAULT 'matic',
  `owner_user_id` INT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel master_part
CREATE TABLE IF NOT EXISTS `master_part` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `master_motor_id` INT NOT NULL,
  `nama_komponen` VARCHAR(100) NOT NULL,
  `interval_km` INT DEFAULT NULL,
  `interval_bulan` INT DEFAULT NULL,
  `kode_part_rekomendasi` VARCHAR(100) DEFAULT NULL,
  `aktif` TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (`master_motor_id`) REFERENCES `master_motor`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabel user_motor
CREATE TABLE IF NOT EXISTS `user_motor` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `master_motor_id` INT NOT NULL,
  `nomor_polisi` VARCHAR(20) DEFAULT NULL,
  `odometer_saat_ini` INT NOT NULL,
  `odometer_diperbarui_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`master_motor_id`) REFERENCES `master_motor`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabel user_part_status
CREATE TABLE IF NOT EXISTS `user_part_status` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_motor_id` INT NOT NULL,
  `master_part_id` INT DEFAULT NULL,
  `nama_komponen` VARCHAR(100) NOT NULL,
  `interval_km` INT DEFAULT NULL,
  `interval_bulan` INT DEFAULT NULL,
  `kode_part` VARCHAR(100) DEFAULT NULL,
  `odometer_baseline` INT NOT NULL,
  `tanggal_baseline` DATE NOT NULL,
  `prev_odometer_baseline` INT DEFAULT NULL,
  `prev_tanggal_baseline` DATE DEFAULT NULL,
  FOREIGN KEY (`user_motor_id`) REFERENCES `user_motor`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tabel riwayat_servis
CREATE TABLE IF NOT EXISTS `riwayat_servis` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_motor_id` INT NOT NULL,
  `user_part_status_id` INT DEFAULT NULL,
  `nama_komponen` VARCHAR(100) NOT NULL,
  `kode_part` VARCHAR(100) DEFAULT NULL,
  `odometer_saat_ganti` INT NOT NULL,
  `tanggal_ganti` DATE NOT NULL,
  `catatan` TEXT DEFAULT NULL,
  `biaya` INT DEFAULT 0,
  `bengkel` VARCHAR(150) DEFAULT NULL,
  `status` ENUM('aktif', 'dibatalkan') NOT NULL DEFAULT 'aktif',
  `created_at` DATETIME NOT NULL,
  `foto_nota` VARCHAR(255) DEFAULT NULL,
  FOREIGN KEY (`user_motor_id`) REFERENCES `user_motor`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Tabel riwayat_odometer (Usulan Tabel ke-7 dari PRD)
CREATE TABLE IF NOT EXISTS `riwayat_odometer` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_motor_id` INT NOT NULL,
  `nilai` INT NOT NULL,
  `dicatat_at` DATETIME NOT NULL,
  FOREIGN KEY (`user_motor_id`) REFERENCES `user_motor`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- SEED DATA
-- ==========================================================

-- Password default: password123 (kecuali admin: admin12345)
INSERT INTO `users` (`id`, `nama`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Dimas Prasetyo', 'dimas@motocare.id', '$2y$10$wJkXoH2v6PZJ47M5zFkJ6uJzC3vYkYf99o8WzK9e4s9L8c4zN2G1a', 'pengguna', '2024-12-14 08:30:00'),
(2, 'Admin Tim Hore', 'admin@motocare.id', '$2y$10$3Y3J8s.11eOlnz5B4kXN2.8Lw8V6GqI4fV1Q3K5q6w7e8r9t0y1u2', 'admin', '2024-12-01 00:00:00'),
(3, 'Budi Santoso (Ojol)', 'budi@motocare.id', '$2y$10$wJkXoH2v6PZJ47M5zFkJ6uJzC3vYkYf99o8WzK9e4s9L8c4zN2G1a', 'pengguna', '2025-01-10 10:00:00');

INSERT INTO `master_motor` (`id`, `merek`, `tipe`, `varian`, `aktif`, `jenis_motor`) VALUES
(1, 'Honda', 'Vario 160 CBS', '2023', 1, 'matic'),
(2, 'Honda', 'BeAT 110 eSP', '2023', 1, 'matic'),
(3, 'Honda', 'PCX 160 ABS', '2023', 1, 'matic'),
(4, 'Yamaha', 'NMAX 155 Connected', '2023', 1, 'matic'),
(5, 'Yamaha', 'Aerox 155 VVA', '2023', 1, 'matic'),
(6, 'Suzuki', 'Address 110 FI', '2022', 1, 'matic');

INSERT INTO `master_part` (`id`, `master_motor_id`, `nama_komponen`, `interval_km`, `interval_bulan`, `kode_part_rekomendasi`, `aktif`) VALUES
(1, 1, 'Oli Mesin (MPX2 10W-30 0.8L)', 2000, 2, '08232-2MB-K0LN', 1),
(2, 1, 'Filter Udara (Air Cleaner)', 8000, 8, '17210-K0S-V00', 1),
(3, 1, 'Busi (Spark Plug NGK CPR9EA-9)', 8000, 8, '31916-K97-T01', 1),
(4, 1, 'Kampas Rem Depan (Pad Set Fr)', 10000, 12, '06455-KRE-K01', 1),
(5, 1, 'Kampas Rem Belakang (Shoe Set Rr)', 10000, 12, '43130-KZL-930', 1),
(6, 1, 'Oli Gardan / Transmisi (120ml)', 8000, 8, '08234-2MB-K00', 1),
(7, 1, 'V-Belt & Roller Kit Drive', 24000, 24, '23100-K0S-V01', 1),
(8, 1, 'Cairan Pendingin (Radiator Coolant)', 12000, 12, '08CLA-M99-11LL1', 1);

INSERT INTO `user_motor` (`id`, `user_id`, `master_motor_id`, `nomor_polisi`, `odometer_saat_ini`, `odometer_diperbarui_at`, `created_at`) VALUES
(1, 1, 1, 'B 4821 KRO', 11200, NOW() - INTERVAL 2 DAY, '2024-12-14 09:00:00');

INSERT INTO `user_part_status` (`id`, `user_motor_id`, `master_part_id`, `nama_komponen`, `interval_km`, `interval_bulan`, `kode_part`, `odometer_baseline`, `tanggal_baseline`) VALUES
(1, 1, 1, 'Oli Mesin (MPX2 10W-30 0.8L)', 2000, 2, '08232-2MB-K0LN', 9200, CURDATE() - INTERVAL 61 DAY),
(2, 1, 2, 'Filter Udara (Air Cleaner)', 8000, 8, '17210-K0S-V00', 3500, CURDATE() - INTERVAL 180 DAY),
(3, 1, 3, 'Busi (Spark Plug NGK CPR9EA-9)', 8000, 8, '31916-K97-T01', 8000, CURDATE() - INTERVAL 90 DAY),
(4, 1, 4, 'Kampas Rem Depan (Pad Set Fr)', 10000, 12, '06455-KRE-K01', 6000, CURDATE() - INTERVAL 120 DAY),
(5, 1, 5, 'Kampas Rem Belakang (Shoe Set Rr)', 10000, 12, '43130-KZL-930', 7000, CURDATE() - INTERVAL 100 DAY),
(6, 1, 6, 'Oli Gardan / Transmisi (120ml)', 8000, 8, '08234-2MB-K00', 8000, CURDATE() - INTERVAL 90 DAY);

INSERT INTO `riwayat_servis` (`id`, `user_motor_id`, `user_part_status_id`, `nama_komponen`, `kode_part`, `odometer_saat_ganti`, `tanggal_ganti`, `catatan`, `biaya`, `bengkel`, `status`, `created_at`) VALUES
(1, 1, 1, 'Oli Mesin (MPX2 10W-30 0.8L)', '08232-2MB-K0LN', 9200, CURDATE() - INTERVAL 61 DAY, 'Penggantian rutin berkala', 55000, 'AHASS 07123 Daya Motor Depok', 'aktif', NOW() - INTERVAL 61 DAY),
(2, 1, 3, 'Busi (NGK CPR9EA-9)', '31916-K97-T01', 8000, CURDATE() - INTERVAL 90 DAY, 'Ganti busi tarikan lebih enteng', 35000, 'AHASS 07123 Daya Motor Depok', 'aktif', NOW() - INTERVAL 90 DAY),
(3, 1, 6, 'Oli Gardan / Transmisi', '08234-2MB-K00', 8000, CURDATE() - INTERVAL 90 DAY, 'Kuras dan ganti oli transmisi', 25000, 'AHASS 07123 Daya Motor Depok', 'aktif', NOW() - INTERVAL 90 DAY),
(4, 1, 1, 'Oli Mesin (MPX2 10W-30 0.8L)', '08232-2MB-K0LN', 7200, CURDATE() - INTERVAL 120 DAY, 'Ganti oli berkala 7.000 km', 55000, 'Bengkel Mitra Motor Margonda', 'aktif', NOW() - INTERVAL 120 DAY),
(5, 1, 4, 'Kampas Rem Depan', '06455-KRE-K01', 6000, CURDATE() - INTERVAL 150 DAY, 'Ganti kampas rem depan AHM asli', 65000, 'AHASS 07123 Daya Motor Depok', 'aktif', NOW() - INTERVAL 150 DAY);

INSERT INTO `riwayat_odometer` (`user_motor_id`, `nilai`, `dicatat_at`) VALUES
(1, 9200, NOW() - INTERVAL 61 DAY),
(1, 10000, NOW() - INTERVAL 30 DAY),
(1, 10800, NOW() - INTERVAL 10 DAY),
(1, 11200, NOW() - INTERVAL 2 DAY);
