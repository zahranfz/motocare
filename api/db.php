<?php
/**
 * MotoCare Digital - Database Connection & Initialization
 * Supports SQLite (zero-config default) and MySQL/MariaDB (XAMPP).
 */

date_default_timezone_set('Asia/Jakarta');

function getDB() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $dbType = 'sqlite'; // 'sqlite' or 'mysql'
    $dbPath = __DIR__ . '/../data/motocare.db';

    // If MySQL config is requested or MySQL is active:
    $mysqlHost = '127.0.0.1';
    $mysqlDb = 'motocare';
    $mysqlUser = 'root';
    $mysqlPass = '';

    // Check if we can/should use MySQL
    if (file_exists(__DIR__ . '/../use_mysql.flag')) {
        try {
            $pdo = new PDO("mysql:host={$mysqlHost};dbname={$mysqlDb};charset=utf8mb4", $mysqlUser, $mysqlPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (Exception $e) {
            // Fallback to SQLite
        }
    }

    // Default to SQLite for seamless portability and instant running
    if (!file_exists(dirname($dbPath))) {
        mkdir(dirname($dbPath), 0777, true);
    }

    $isNew = !file_exists($dbPath);
    $pdo = new PDO("sqlite:" . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Enable foreign keys in SQLite
    $pdo->exec("PRAGMA foreign_keys = ON;");

    if ($isNew || filesize($dbPath) === 0) {
        initSchemaAndSeed($pdo);
    }

    return $pdo;
}

function initSchemaAndSeed($pdo) {
    // 1. users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'pengguna',
        created_at DATETIME NOT NULL
    )");

    // 2. master_motor
    $pdo->exec("CREATE TABLE IF NOT EXISTS master_motor (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        merek TEXT NOT NULL,
        tipe TEXT NOT NULL,
        varian TEXT,
        aktif INTEGER NOT NULL DEFAULT 1
    )");

    // 3. master_part
    $pdo->exec("CREATE TABLE IF NOT EXISTS master_part (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        master_motor_id INTEGER NOT NULL,
        nama_komponen TEXT NOT NULL,
        interval_km INTEGER,
        interval_bulan INTEGER,
        kode_part_rekomendasi TEXT,
        aktif INTEGER NOT NULL DEFAULT 1,
        FOREIGN KEY (master_motor_id) REFERENCES master_motor(id)
    )");

    // 4. user_motor
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_motor (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        master_motor_id INTEGER NOT NULL,
        nomor_polisi TEXT,
        odometer_saat_ini INTEGER NOT NULL,
        odometer_diperbarui_at DATETIME NOT NULL,
        created_at DATETIME NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (master_motor_id) REFERENCES master_motor(id)
    )");

    // 5. user_part_status
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_part_status (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_motor_id INTEGER NOT NULL,
        master_part_id INTEGER,
        nama_komponen TEXT NOT NULL,
        interval_km INTEGER,
        interval_bulan INTEGER,
        kode_part TEXT,
        odometer_baseline INTEGER NOT NULL,
        tanggal_baseline DATE NOT NULL,
        prev_odometer_baseline INTEGER,
        prev_tanggal_baseline DATE,
        FOREIGN KEY (user_motor_id) REFERENCES user_motor(id)
    )");

    // 6. riwayat_servis
    $pdo->exec("CREATE TABLE IF NOT EXISTS riwayat_servis (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_motor_id INTEGER NOT NULL,
        user_part_status_id INTEGER,
        nama_komponen TEXT NOT NULL,
        kode_part TEXT,
        odometer_saat_ganti INTEGER NOT NULL,
        tanggal_ganti DATE NOT NULL,
        catatan TEXT,
        biaya INTEGER DEFAULT 0,
        bengkel TEXT,
        status TEXT NOT NULL DEFAULT 'aktif',
        created_at DATETIME NOT NULL,
        FOREIGN KEY (user_motor_id) REFERENCES user_motor(id)
    )");

    // 7. riwayat_odometer
    $pdo->exec("CREATE TABLE IF NOT EXISTS riwayat_odometer (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_motor_id INTEGER NOT NULL,
        nilai INTEGER NOT NULL,
        dicatat_at DATETIME NOT NULL,
        FOREIGN KEY (user_motor_id) REFERENCES user_motor(id)
    )");

    seedInitialData($pdo);
}

function seedInitialData($pdo) {
    // Seed Users
    $now = date('Y-m-d H:i:s');
    $pwHash = password_hash('password123', PASSWORD_BCRYPT);
    $adminPwHash = password_hash('admin12345', PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (nama, email, password_hash, role, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['Dimas Prasetyo', 'dimas@motocare.id', $pwHash, 'pengguna', '2024-12-14 08:30:00']);
    $dimasId = $pdo->lastInsertId();

    $stmt->execute(['Admin Tim Hore', 'admin@motocare.id', $adminPwHash, 'admin', '2024-12-01 00:00:00']);
    $adminId = $pdo->lastInsertId();

    $stmt->execute(['Budi Santoso (Ojol)', 'budi@motocare.id', $pwHash, 'pengguna', '2025-01-10 10:00:00']);
    $budiId = $pdo->lastInsertId();

    // Seed Master Motor
    $motorStmt = $pdo->prepare("INSERT INTO master_motor (merek, tipe, varian, aktif) VALUES (?, ?, ?, 1)");
    
    $motorStmt->execute(['Honda', 'Vario 160 CBS', '2023']);
    $varioId = $pdo->lastInsertId();

    $motorStmt->execute(['Honda', 'BeAT 110 eSP', '2023']);
    $beatId = $pdo->lastInsertId();

    $motorStmt->execute(['Honda', 'PCX 160 ABS', '2023']);
    $pcxId = $pdo->lastInsertId();

    $motorStmt->execute(['Yamaha', 'NMAX 155 Connected', '2023']);
    $nmaxId = $pdo->lastInsertId();

    $motorStmt->execute(['Yamaha', 'Aerox 155 VVA', '2023']);
    $aeroxId = $pdo->lastInsertId();

    $motorStmt->execute(['Suzuki', 'Address 110 FI', '2022']);
    $suzukiId = $pdo->lastInsertId();

    // Seed Master Part for Vario 160
    $partStmt = $pdo->prepare("INSERT INTO master_part (master_motor_id, nama_komponen, interval_km, interval_bulan, kode_part_rekomendasi, aktif) VALUES (?, ?, ?, ?, ?, 1)");
    
    // Vario 160 parts
    $partsVario = [
        ['Oli Mesin (MPX2 10W-30 0.8L)', 2000, 2, '08232-2MB-K0LN'],
        ['Filter Udara (Air Cleaner)', 12000, 12, '17210-K0S-V00'],
        ['Busi (Spark Plug NGK CPR9EA-9)', 8000, 8, '31916-K97-T01'],
        ['Kampas Rem Depan (Pad Set Fr)', 10000, 12, '06455-KRE-K01'],
        ['Kampas Rem Belakang (Shoe Set Rr)', 10000, 12, '43130-KZL-930'],
        ['Oli Gardan / Transmisi (120ml)', 8000, 8, '08234-2MB-K00'],
        ['V-Belt & Roller Kit Drive', 24000, 24, '23100-K0S-V01'],
        ['Cairan Pendingin (Radiator Coolant)', 12000, 12, '08CLA-M99-11LL1'],
    ];
    foreach ($partsVario as $p) {
        $partStmt->execute([$varioId, $p[0], $p[1], $p[2], $p[3]]);
    }

    // BeAT 110 parts
    $partsBeat = [
        ['Oli Mesin (MPX2 10W-30 0.8L)', 2000, 2, '08232-2MB-K0LN'],
        ['Oli Gardan (120ml)', 8000, 8, '08234-2MB-K00'],
        ['Busi (NGK CPR9EA-9)', 8000, 8, '31916-KRM-841'],
        ['Filter Udara', 12000, 12, '17210-K16-900'],
        ['V-Belt Kit', 24000, 24, '23100-K44-V01'],
    ];
    foreach ($partsBeat as $p) {
        $partStmt->execute([$beatId, $p[0], $p[1], $p[2], $p[3]]);
    }

    // NMAX 155 parts
    $partsNmax = [
        ['Oli Mesin Yamalube Super Matic 1L', 3000, 3, '90793-AJ446'],
        ['Oli Gardan Yamalube 150ml', 10000, 10, '90793-AJ802'],
        ['Busi NGK CPR8EA-9', 9000, 9, '94700-00439'],
        ['Filter Udara NMAX', 12000, 12, 'B65-E4451-00'],
        ['V-Belt Kit NMAX', 25000, 24, 'B65-E7641-00'],
    ];
    foreach ($partsNmax as $p) {
        $partStmt->execute([$nmaxId, $p[0], $p[1], $p[2], $p[3]]);
    }

    // Seed Dimas's Motor (Honda Vario 160 CBS)
    $stmtMotor = $pdo->prepare("INSERT INTO user_motor (user_id, master_motor_id, nomor_polisi, odometer_saat_ini, odometer_diperbarui_at, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtMotor->execute([$dimasId, $varioId, 'B 4821 KRO', 11200, date('Y-m-d H:i:s', strtotime('-2 days')), '2024-12-14 09:00:00']);
    $userMotorId = $pdo->lastInsertId();

    // Populate user_part_status for Dimas's motor
    // We calibrate baselines so that:
    // 1. Oli Mesin -> RED (Odometer 11.200, Baseline 9.200 => Sisa km = 2000 - 2000 = 0 km <= 0 => MERAH)
    // 2. Filter Udara -> YELLOW (Odometer 11.200, Baseline 3.500, Interval 8000 => diff = 7700 => Sisa km = 300 km < 500 => KUNING)
    // 3. Busi -> GREEN (Baseline 8.000 => diff = 3200 => Sisa km = 4800 km => HIJAU)
    // 4. Kampas Rem Depan -> GREEN (Baseline 6.000 => diff = 5200 => Sisa km = 4800 km => HIJAU)
    // 5. Kampas Rem Belakang -> GREEN (Baseline 7.000 => diff = 4200 => Sisa km = 5800 km => HIJAU)
    // 6. Oli Gardan -> GREEN (Baseline 8.000 => diff = 3200 => Sisa km = 4800 km => HIJAU)
    // Exactly matches the Stitch Red & Beige Dashboard: 1 Merah, 1 Kuning, 4 Hijau!

    $statusStmt = $pdo->prepare("INSERT INTO user_part_status (user_motor_id, master_part_id, nama_komponen, interval_km, interval_bulan, kode_part, odometer_baseline, tanggal_baseline) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    $statusStmt->execute([$userMotorId, 1, 'Oli Mesin (MPX2 10W-30 0.8L)', 2000, 2, '08232-2MB-K0LN', 9200, date('Y-m-d', strtotime('-61 days'))]);
    $oliStatusId = $pdo->lastInsertId();

    $statusStmt->execute([$userMotorId, 2, 'Filter Udara (Air Cleaner)', 8000, 8, '17210-K0S-V00', 3500, date('Y-m-d', strtotime('-180 days'))]);
    $filterStatusId = $pdo->lastInsertId();

    $statusStmt->execute([$userMotorId, 3, 'Busi (Spark Plug NGK CPR9EA-9)', 8000, 8, '31916-K97-T01', 8000, date('Y-m-d', strtotime('-90 days'))]);
    $busiStatusId = $pdo->lastInsertId();

    $statusStmt->execute([$userMotorId, 4, 'Kampas Rem Depan (Pad Set Fr)', 10000, 12, '06455-KRE-K01', 6000, date('Y-m-d', strtotime('-120 days'))]);
    $remDepanStatusId = $pdo->lastInsertId();

    $statusStmt->execute([$userMotorId, 5, 'Kampas Rem Belakang (Shoe Set Rr)', 10000, 12, '43130-KZL-930', 7000, date('Y-m-d', strtotime('-100 days'))]);
    $remBelakangStatusId = $pdo->lastInsertId();

    $statusStmt->execute([$userMotorId, 6, 'Oli Gardan / Transmisi (120ml)', 8000, 8, '08234-2MB-K00', 8000, date('Y-m-d', strtotime('-90 days'))]);
    $gardanStatusId = $pdo->lastInsertId();

    // Seed riwayat_servis for Dimas
    $servisStmt = $pdo->prepare("INSERT INTO riwayat_servis (user_motor_id, user_part_status_id, nama_komponen, kode_part, odometer_saat_ganti, tanggal_ganti, catatan, biaya, bengkel, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'aktif', ?)");
    
    $servisStmt->execute([$userMotorId, $oliStatusId, 'Oli Mesin (MPX2 10W-30 0.8L)', '08232-2MB-K0LN', 9200, date('Y-m-d', strtotime('-61 days')), 'Penggantian rutin berkala', 55000, 'AHASS 07123 Daya Motor Depok', date('Y-m-d H:i:s', strtotime('-61 days'))]);
    $servisStmt->execute([$userMotorId, $busiStatusId, 'Busi (NGK CPR9EA-9)', '31916-K97-T01', 8000, date('Y-m-d', strtotime('-90 days')), 'Ganti busi tarikan lebih enteng', 35000, 'AHASS 07123 Daya Motor Depok', date('Y-m-d H:i:s', strtotime('-90 days'))]);
    $servisStmt->execute([$userMotorId, $gardanStatusId, 'Oli Gardan / Transmisi', '08234-2MB-K00', 8000, date('Y-m-d', strtotime('-90 days')), 'Kuras dan ganti oli transmisi', 25000, 'AHASS 07123 Daya Motor Depok', date('Y-m-d H:i:s', strtotime('-90 days'))]);
    $servisStmt->execute([$userMotorId, $oliStatusId, 'Oli Mesin (MPX2 10W-30 0.8L)', '08232-2MB-K0LN', 7200, date('Y-m-d', strtotime('-120 days')), 'Ganti oli berkala 7.000 km', 55000, 'Bengkel Mitra Motor Margonda', date('Y-m-d H:i:s', strtotime('-120 days'))]);
    $servisStmt->execute([$userMotorId, $remDepanStatusId, 'Kampas Rem Depan', '06455-KRE-K01', 6000, date('Y-m-d', strtotime('-150 days')), 'Ganti kampas rem depan AHM asli', 65000, 'AHASS 07123 Daya Motor Depok', date('Y-m-d H:i:s', strtotime('-150 days'))]);

    // Seed riwayat_odometer
    $odoStmt = $pdo->prepare("INSERT INTO riwayat_odometer (user_motor_id, nilai, dicatat_at) VALUES (?, ?, ?)");
    $odoStmt->execute([$userMotorId, 9200, date('Y-m-d H:i:s', strtotime('-61 days'))]);
    $odoStmt->execute([$userMotorId, 10000, date('Y-m-d H:i:s', strtotime('-30 days'))]);
    $odoStmt->execute([$userMotorId, 10800, date('Y-m-d H:i:s', strtotime('-10 days'))]);
    $odoStmt->execute([$userMotorId, 11200, date('Y-m-d H:i:s', strtotime('-2 days'))]);
}
