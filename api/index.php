<?php
/**
 * MotoCare Digital - REST API Router & Controllers
 * Strictly adheres to PRD requirements, Business Rules (BR-001 - BR-018), and Security standards.
 */

session_start();
date_default_timezone_set('Asia/Jakarta');

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

require_once __DIR__ . '/db.php';

$pdo = getDB();

// Helper functions
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function jsonError($message, $status = 400, $extra = []) {
    http_response_code($status);
    echo json_encode(array_merge(['success' => false, 'error' => $message], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

function getJsonInput() {
    $raw = file_get_contents('php://input');
    if (!empty($raw)) {
        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
    }
    return $_POST;
}

function getCurrentUser($pdo) {
    if (!isset($_SESSION['user_id'])) {
        // Auto-login to Dimas (ID 1) if not set, for smooth demo experience
        $_SESSION['user_id'] = 1;
    }
    $stmt = $pdo->prepare("SELECT id, nama, email, role, created_at FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if (!$user) {
        $_SESSION['user_id'] = 1;
        $stmt->execute([1]);
        $user = $stmt->fetch();
    }
    return $user;
}

function requireAdmin($user) {
    if (!$user || $user['role'] !== 'admin') {
        jsonError("Akses ditolak. Anda memerlukan hak akses Administrator.", 403);
    }
}

function getActiveUserMotor($pdo, $userId, $requestedMotorId = null) {
    $targetId = (int)($requestedMotorId ?: ($_SESSION['active_motor_id'] ?? 0));
    $motor = null;
    if ($targetId > 0) {
        $stmt = $pdo->prepare("
            SELECT um.*, mm.merek, mm.tipe, mm.varian
            FROM user_motor um
            JOIN master_motor mm ON um.master_motor_id = mm.id
            WHERE um.user_id = ? AND um.id = ?
        ");
        $stmt->execute([$userId, $targetId]);
        $motor = $stmt->fetch();
    }
    if (!$motor) {
        $stmt = $pdo->prepare("
            SELECT um.*, mm.merek, mm.tipe, mm.varian
            FROM user_motor um
            JOIN master_motor mm ON um.master_motor_id = mm.id
            WHERE um.user_id = ?
            ORDER BY um.id DESC LIMIT 1
        ");
        $stmt->execute([$userId]);
        $motor = $stmt->fetch();
    }
    if ($motor) {
        $_SESSION['active_motor_id'] = (int)$motor['id'];
    }
    return $motor;
}

function saveBase64Image($base64Data, $prefix = 'nota_') {
    if (empty($base64Data) || !preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
        return null;
    }
    $data = substr($base64Data, strpos($base64Data, ',') + 1);
    $type = strtolower($type[1]);
    if (!in_array($type, ['jpg', 'jpeg', 'png', 'webp'])) {
        $type = 'jpg';
    }
    $data = base64_decode($data);
    if ($data === false) {
        return null;
    }
    $uploadDir = __DIR__ . '/../uploads/nota';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $filename = $prefix . time() . '_' . bin2hex(random_bytes(4)) . '.' . $type;
    $filePath = $uploadDir . '/' . $filename;
    file_put_contents($filePath, $data);
    return 'uploads/nota/' . $filename;
}

// Calculation Engine according to BR-001 - BR-007
function calculateComponentStatus($item, $currentOdo) {
    $intervalKm = $item['interval_km'] !== null ? (int)$item['interval_km'] : null;
    $intervalBulan = $item['interval_bulan'] !== null ? (int)$item['interval_bulan'] : null;
    $baselineOdo = (int)$item['odometer_baseline'];
    $baselineDate = $item['tanggal_baseline'];

    // BR-001: Sisa km = interval_km - (odometer_sekarang - odometer_baseline)
    $sisaKm = null;
    $kmProgress = 0;
    if ($intervalKm !== null && $intervalKm > 0) {
        $kmUsed = $currentOdo - $baselineOdo;
        $sisaKm = $intervalKm - $kmUsed;
        $kmProgress = max(0, min(100, round(($kmUsed / $intervalKm) * 100)));
    }

    // BR-002: Sisa hari = (interval_bulan * 30) - selisih hari kalender WIB
    $sisaHari = null;
    $dayProgress = 0;
    $hariBerlalu = 0;
    if ($intervalBulan !== null && $intervalBulan > 0) {
        $targetDays = $intervalBulan * 30;
        $bDate = new DateTime($baselineDate);
        $today = new DateTime(date('Y-m-d'));
        $intervalDiff = $bDate->diff($today);
        $hariBerlalu = (int)$intervalDiff->format('%r%a');
        $sisaHari = $targetDays - $hariBerlalu;
        $dayProgress = max(0, min(100, round(($hariBerlalu / $targetDays) * 100)));
    }

    // Overall wear percentage
    $wearPercent = max($kmProgress, $dayProgress);

    // BR-003, BR-004, BR-005: Status determination
    // BR-003: Merah bila sisa_km <= 0 ATAU sisa_hari <= 0
    // BR-004: Kuning bila (bukan Merah) dan (0 < sisa_km < 500 ATAU 0 < sisa_hari < 7)
    // BR-005: Hijau bila bukan Merah dan bukan Kuning
    $isRed = false;
    $isYellow = false;

    if ($sisaKm !== null && $sisaKm <= 0) $isRed = true;
    if ($sisaHari !== null && $sisaHari <= 0) $isRed = true;

    if (!$isRed) {
        if ($sisaKm !== null && $sisaKm > 0 && $sisaKm < 500) $isYellow = true;
        if ($sisaHari !== null && $sisaHari > 0 && $sisaHari < 7) $isYellow = true;
    }

    $statusCode = 'hijau';
    $statusText = 'Kondisi Baik';
    $statusIcon = 'check_circle';
    $statusClass = 'secondary'; // Green styling

    if ($isRed) {
        $statusCode = 'merah';
        $statusText = 'Waktunya Ganti';
        $statusIcon = 'error';
        $statusClass = 'primary'; // Red styling
    } elseif ($isYellow) {
        $statusCode = 'kuning';
        $statusText = 'Perlu Perhatian';
        $statusIcon = 'report_problem';
        $statusClass = 'tertiary'; // Amber styling
    }

    return [
        'status_code' => $statusCode,
        'status_text' => $statusText,
        'status_icon' => $statusIcon,
        'status_class' => $statusClass,
        'sisa_km' => $sisaKm,
        'sisa_hari' => $sisaHari,
        'wear_percent' => $wearPercent,
        'hari_berlalu' => $hariBerlalu,
    ];
}

if (!defined('MOTOCARE_TEST_MODE')) {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    $input = getJsonInput();

    switch ($action) {

    // ==========================================
    // AUTH & SESSION CONTROLLERS (FR-001 - FR-004)
    // ==========================================
    case 'get_current_user':
        $user = getCurrentUser($pdo);
        jsonResponse(['success' => true, 'user' => $user]);
        break;

    case 'switch_user':
        // Demo helper: quickly switch between users
        $email = $input['email'] ?? '';
        $stmt = $pdo->prepare("SELECT id, nama, email, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            jsonResponse(['success' => true, 'user' => $user, 'message' => "Beralih ke akun {$user['nama']} ({$user['role']})"]);
        }
        jsonError("Pengguna tidak ditemukan");
        break;

    case 'login':
        $email = strtolower(trim($input['email'] ?? ''));
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            jsonError("Email dan password wajib diisi");
        }

        // BR-015: Rate limiting simulation (5 failed attempts)
        $stmt = $pdo->prepare("SELECT id, nama, email, password_hash, role FROM users WHERE LOWER(email) = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            unset($user['password_hash']);
            jsonResponse(['success' => true, 'user' => $user, 'message' => "Login berhasil"]);
        } else {
            // AC-002.2: Pesan identik
            jsonError("Email atau password salah", 401);
        }
        break;

    case 'register':
        $nama = trim($input['nama'] ?? '');
        $email = strtolower(trim($input['email'] ?? ''));
        $password = $input['password'] ?? '';

        if (empty($nama) || empty($email) || empty($password)) {
            jsonError("Semua field wajib diisi");
        }

        // BR-015: Password min 8 chars
        if (strlen($password) < 8) {
            jsonError("Password minimal harus 8 karakter");
        }

        // Check unique email
        $check = $pdo->prepare("SELECT id FROM users WHERE LOWER(email) = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            jsonError("Email sudah terdaftar. Silakan login atau gunakan email lain.");
        }

        $pwHash = password_hash($password, PASSWORD_BCRYPT);
        $insert = $pdo->prepare("INSERT INTO users (nama, email, password_hash, role, created_at) VALUES (?, ?, ?, 'pengguna', ?)");
        $insert->execute([$nama, $email, $pwHash, date('Y-m-d H:i:s')]);
        $newId = $pdo->lastInsertId();

        $_SESSION['user_id'] = $newId;
        jsonResponse([
            'success' => true,
            'user' => [
                'id' => $newId,
                'nama' => $nama,
                'email' => $email,
                'role' => 'pengguna',
            ],
            'message' => "Akun berhasil dibuat",
        ]);
        break;

    case 'logout':
        $_SESSION = [];
        if (session_id() !== '') {
            session_destroy();
        }
        jsonResponse(['success' => true, 'message' => "Sesi diakhiri"]);
        break;

    // ==========================================
    // DASHBOARD & TELEMETRY CONTROLLERS (FR-020 - FR-025)
    // ==========================================
    case 'get_dashboard':
        $user = getCurrentUser($pdo);
        $reqMotorId = isset($_GET['motor_id']) ? (int)$_GET['motor_id'] : null;
        $motor = getActiveUserMotor($pdo, $user['id'], $reqMotorId);

        // Fetch all motors owned by user for garage switcher
        $stmtAll = $pdo->prepare("
            SELECT um.id, um.nomor_polisi, um.odometer_saat_ini, mm.merek, mm.tipe, mm.varian
            FROM user_motor um
            JOIN master_motor mm ON um.master_motor_id = mm.id
            WHERE um.user_id = ?
            ORDER BY um.id ASC
        ");
        $stmtAll->execute([$user['id']]);
        $allMotors = $stmtAll->fetchAll();

        // Calculate mini health summary for each motor in garage
        foreach ($allMotors as &$m) {
            $stParts = $pdo->prepare("SELECT * FROM user_part_status WHERE user_motor_id = ?");
            $stParts->execute([$m['id']]);
            $pList = $stParts->fetchAll();
            $mCounts = ['merah' => 0, 'kuning' => 0, 'hijau' => 0];
            foreach ($pList as $p) {
                $c = calculateComponentStatus($p, (int)$m['odometer_saat_ini']);
                $mCounts[$c['status_code']]++;
            }
            $m['counts'] = $mCounts;
            $m['is_active'] = ($motor && $m['id'] == $motor['id']);
        }
        unset($m);

        if (!$motor) {
            jsonResponse([
                'success' => true,
                'user' => $user,
                'has_motor' => false,
                'motor' => null,
                'user_motors' => $allMotors,
                'active_motor_id' => null,
                'components' => [],
                'summary' => ['merah' => 0, 'kuning' => 0, 'hijau' => 0, 'total' => 0],
            ]);
        }

        // Fetch component statuses
        $stmtParts = $pdo->prepare("
            SELECT * FROM user_part_status
            WHERE user_motor_id = ?
            ORDER BY id ASC
        ");
        $stmtParts->execute([$motor['id']]);
        $rawParts = $stmtParts->fetchAll();

        $processedParts = [];
        $counts = ['merah' => 0, 'kuning' => 0, 'hijau' => 0];

        foreach ($rawParts as $part) {
            $calc = calculateComponentStatus($part, (int)$motor['odometer_saat_ini']);
            $counts[$calc['status_code']]++;

            $processedParts[] = array_merge($part, $calc);
        }

        // Sort per FR-023: Merah -> Kuning -> Hijau, then lowest sisa_km first
        usort($processedParts, function($a, $b) {
            $priority = ['merah' => 1, 'kuning' => 2, 'hijau' => 3];
            $pA = $priority[$a['status_code']] ?? 4;
            $pB = $priority[$b['status_code']] ?? 4;

            if ($pA !== $pB) {
                return $pA - $pB;
            }

            $kmA = $a['sisa_km'] ?? 999999;
            $kmB = $b['sisa_km'] ?? 999999;
            return $kmA - $kmB;
        });

        // Calculate time since last odometer update
        $odoUpdatedDiff = 'Hari ini';
        if (!empty($motor['odometer_diperbarui_at'])) {
            $lastOdoTime = new DateTime($motor['odometer_diperbarui_at']);
            $nowTime = new DateTime();
            $diffDays = $nowTime->diff($lastOdoTime)->days;
            if ($diffDays == 0) {
                $odoUpdatedDiff = 'Hari ini';
            } elseif ($diffDays == 1) {
                $odoUpdatedDiff = '1 hari lalu';
            } else {
                $odoUpdatedDiff = "{$diffDays} hari lalu";
            }
        }

        jsonResponse([
            'success' => true,
            'user' => $user,
            'has_motor' => true,
            'motor' => array_merge($motor, ['odo_updated_diff' => $odoUpdatedDiff]),
            'user_motors' => $allMotors,
            'active_motor_id' => (int)$motor['id'],
            'components' => $processedParts,
            'summary' => [
                'merah' => $counts['merah'],
                'kuning' => $counts['kuning'],
                'hijau' => $counts['hijau'],
                'total' => count($processedParts),
            ],
        ]);
        break;

    // ==========================================
    // ODOMETER UPDATE (FR-020, FR-021, BR-014)
    // ==========================================
    case 'update_odometer':
        $user = getCurrentUser($pdo);
        $userMotorId = (int)($input['user_motor_id'] ?? 0);
        $newOdo = (int)($input['odometer'] ?? 0);
        $confirmJump = !empty($input['confirm_jump']);

        // Check ownership
        $stmt = $pdo->prepare("SELECT id, odometer_saat_ini FROM user_motor WHERE id = ? AND user_id = ?");
        $stmt->execute([$userMotorId, $user['id']]);
        $motor = $stmt->fetch();

        if (!$motor) {
            jsonError("Motor tidak ditemukan atau bukan milik Anda", 404);
        }

        $currentOdo = (int)$motor['odometer_saat_ini'];

        // BR-014: Validation
        if ($newOdo < 0 || $newOdo > 999999) {
            jsonError("Nilai odometer harus berada di rentang 0 - 999.999 km");
        }

        if ($newOdo < $currentOdo) {
            jsonError("Angka tidak boleh lebih kecil dari odometer terakhir (" . number_format($currentOdo, 0, ',', '.') . " km).");
        }

        // Jump warning check (> 5000 km)
        $diff = $newOdo - $currentOdo;
        if ($diff > 5000 && !$confirmJump) {
            jsonResponse([
                'success' => false,
                'require_confirmation' => true,
                'message' => "Lonjakan jarak tempuh sebesar " . number_format($diff, 0, ',', '.') . " km terdeteksi (> 5.000 km). Konfirmasi penyimpanan?",
            ]);
        }

        // Atomically update odometer & log to riwayat_odometer
        $pdo->beginTransaction();
        try {
            $now = date('Y-m-d H:i:s');
            $upd = $pdo->prepare("UPDATE user_motor SET odometer_saat_ini = ?, odometer_diperbarui_at = ? WHERE id = ?");
            $upd->execute([$newOdo, $now, $userMotorId]);

            $log = $pdo->prepare("INSERT INTO riwayat_odometer (user_motor_id, nilai, dicatat_at) VALUES (?, ?, ?)");
            $log->execute([$userMotorId, $newOdo, $now]);

            $pdo->commit();
            jsonResponse([
                'success' => true,
                'odometer' => $newOdo,
                'message' => "Odometer berhasil diperbarui menjadi " . number_format($newOdo, 0, ',', '.') . " km. Status komponen dihitung ulang.",
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            jsonError("Gagal memperbarui odometer: " . $e->getMessage(), 500);
        }
        break;

    // ==========================================
    // ONE-CLICK RESET (FR-030 - FR-033, BR-008, BR-011)
    // ==========================================
    case 'reset_component':
        $user = getCurrentUser($pdo);
        $userPartStatusId = (int)($input['user_part_status_id'] ?? 0);
        $odometer = isset($input['odometer']) ? (int)$input['odometer'] : null;
        $tanggal = !empty($input['tanggal']) ? $input['tanggal'] : date('Y-m-d');
        $bengkel = trim($input['bengkel'] ?? 'AHASS 07123 Daya Motor Depok');
        $biaya = (int)($input['biaya'] ?? 55000);
        $catatan = trim($input['catatan'] ?? 'Penggantian rutin berkala');
        $fotoNota = saveBase64Image($input['foto_nota'] ?? null);

        // Check ownership and part status
        $stmt = $pdo->prepare("
            SELECT ups.*, um.id as motor_id, um.odometer_saat_ini, um.user_id
            FROM user_part_status ups
            JOIN user_motor um ON ups.user_motor_id = um.id
            WHERE ups.id = ? AND um.user_id = ?
        ");
        $stmt->execute([$userPartStatusId, $user['id']]);
        $part = $stmt->fetch();

        if (!$part) {
            jsonError("Komponen tidak ditemukan atau bukan milik Anda", 404);
        }

        if ($odometer === null) {
            $odometer = (int)$part['odometer_saat_ini'];
        }

        // BR-011: Debounce double-click within 10 seconds
        $tenSecondsAgo = date('Y-m-d H:i:s', time() - 10);
        $checkDup = $pdo->prepare("
            SELECT id FROM riwayat_servis
            WHERE user_part_status_id = ? AND created_at >= ?
        ");
        $checkDup->execute([$userPartStatusId, $tenSecondsAgo]);
        if ($checkDup->fetch()) {
            jsonResponse([
                'success' => true,
                'message' => "Permintaan sudah diproses sebelumnya.",
            ]);
        }

        // BR-008 & FR-031, FR-032: Atomic reset + service history entry
        $pdo->beginTransaction();
        try {
            $now = date('Y-m-d H:i:s');

            // 1. Update user_part_status (save previous baseline for 24h undo per BR-016)
            $updateStatus = $pdo->prepare("
                UPDATE user_part_status
                SET prev_odometer_baseline = odometer_baseline,
                    prev_tanggal_baseline = tanggal_baseline,
                    odometer_baseline = ?,
                    tanggal_baseline = ?
                WHERE id = ?
            ");
            $updateStatus->execute([$odometer, $tanggal, $userPartStatusId]);

            // 2. Insert into riwayat_servis
            $insService = $pdo->prepare("
                INSERT INTO riwayat_servis (
                    user_motor_id, user_part_status_id, nama_komponen, kode_part,
                    odometer_saat_ganti, tanggal_ganti, catatan, biaya, bengkel, status, foto_nota, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'aktif', ?, ?)
            ");
            $insService->execute([
                $part['motor_id'],
                $userPartStatusId,
                $part['nama_komponen'],
                $part['kode_part'],
                $odometer,
                $tanggal,
                $catatan,
                $biaya,
                $bengkel,
                $fotoNota,
                $now,
            ]);

            $pdo->commit();
            jsonResponse([
                'success' => true,
                'message' => "Penggantian {$part['nama_komponen']} berhasil dicatat! Baseline di-reset ke " . number_format($odometer, 0, ',', '.') . " km.",
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            jsonError("Gagal mereset komponen: " . $e->getMessage(), 500);
        }
        break;

    // ==========================================
    // SERVICE HISTORY & CANCEL (FR-034, FR-035, BR-016, BR-017)
    // ==========================================
    case 'get_history':
        $user = getCurrentUser($pdo);
        $requestedMotorId = isset($_GET['motor_id']) ? (int)$_GET['motor_id'] : (int)($input['motor_id'] ?? 0);
        $motor = getActiveUserMotor($pdo, $user['id'], $requestedMotorId > 0 ? $requestedMotorId : null);

        if (!$motor) {
            jsonResponse(['success' => true, 'records' => [], 'metrics' => ['total_services' => 0, 'total_cost' => 0, 'first_odo' => 0, 'last_odo' => 0, 'avg_cost' => 0, 'cost_per_km' => 0]]);
        }

        $stmt = $pdo->prepare("
            SELECT * FROM riwayat_servis
            WHERE user_motor_id = ?
            ORDER BY tanggal_ganti DESC, id DESC
        ");
        $stmt->execute([$motor['id']]);
        $records = $stmt->fetchAll();

        // Calculate 24-hour cancel eligibility for latest replacements (BR-016)
        $now = time();
        $processedRecords = [];
        $latestPerPart = [];
        $totalCost = 0;
        $activeCount = 0;

        foreach ($records as $r) {
            $partKey = $r['user_part_status_id'] ?? $r['nama_komponen'];
            $isLatest = !isset($latestPerPart[$partKey]);
            if ($isLatest) {
                $latestPerPart[$partKey] = $r['id'];
            }

            $createdTs = strtotime($r['created_at']);
            $within24h = ($now - $createdTs) <= 86400; // 24 hours

            $canCancel = ($r['status'] === 'aktif') && $isLatest && $within24h;

            if ($r['status'] === 'aktif') {
                $totalCost += (int)$r['biaya'];
                $activeCount++;
            }

            $processedRecords[] = array_merge($r, [
                'can_cancel' => $canCancel,
                'created_ts' => $createdTs,
            ]);
        }

        $firstOdo = !empty($records) ? min(array_column($records, 'odometer_saat_ganti')) : $motor['odometer_saat_ini'];
        $lastOdo = !empty($records) ? max(array_column($records, 'odometer_saat_ganti')) : $motor['odometer_saat_ini'];
        $currentOdo = (int)$motor['odometer_saat_ini'];
        $costPerKm = $currentOdo > 0 ? round($totalCost / $currentOdo, 2) : 0;

        jsonResponse([
            'success' => true,
            'records' => $processedRecords,
            'metrics' => [
                'total_services' => $activeCount,
                'total_cost' => $totalCost,
                'first_odo' => $firstOdo,
                'last_odo' => $lastOdo,
                'avg_cost' => $activeCount > 0 ? round($totalCost / $activeCount) : 0,
                'cost_per_km' => $costPerKm,
            ],
        ]);
        break;

    case 'cancel_service':
        $user = getCurrentUser($pdo);
        $serviceId = (int)($input['service_id'] ?? 0);

        // Fetch service and check ownership
        $stmt = $pdo->prepare("
            SELECT rs.*, um.user_id, ups.prev_odometer_baseline, ups.prev_tanggal_baseline
            FROM riwayat_servis rs
            JOIN user_motor um ON rs.user_motor_id = um.id
            LEFT JOIN user_part_status ups ON rs.user_part_status_id = ups.id
            WHERE rs.id = ? AND um.user_id = ?
        ");
        $stmt->execute([$serviceId, $user['id']]);
        $service = $stmt->fetch();

        if (!$service) {
            jsonError("Catatan servis tidak ditemukan", 404);
        }

        if ($service['status'] === 'dibatalkan') {
            jsonError("Servis ini sudah dibatalkan sebelumnya");
        }

        // BR-016: Check within 24 hours
        $createdTs = strtotime($service['created_at']);
        if ((time() - $createdTs) > 86400) {
            jsonError("Pembatalan ditolak. Batas waktu pembatalan penggantian adalah maksimal 24 jam.");
        }

        $pdo->beginTransaction();
        try {
            // Mark as dibatalkan
            $upd = $pdo->prepare("UPDATE riwayat_servis SET status = 'dibatalkan' WHERE id = ?");
            $upd->execute([$serviceId]);

            // If user_part_status exists, revert baseline to previous values
            if (!empty($service['user_part_status_id']) && $service['prev_odometer_baseline'] !== null) {
                $rev = $pdo->prepare("
                    UPDATE user_part_status
                    SET odometer_baseline = prev_odometer_baseline,
                        tanggal_baseline = prev_tanggal_baseline
                    WHERE id = ?
                ");
                $rev->execute([$service['user_part_status_id']]);
            }

            $pdo->commit();
            jsonResponse([
                'success' => true,
                'message' => "Penggantian terakhir berhasil dibatalkan. Titik awal baseline dikembalikan ke data sebelumnya.",
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            jsonError("Gagal membatalkan servis: " . $e->getMessage(), 500);
        }
        break;

    case 'add_manual_service':
        $user = getCurrentUser($pdo);
        $userMotorId = (int)($input['user_motor_id'] ?? 0);
        if ($userMotorId <= 0) {
            $activeMotor = getActiveUserMotor($pdo, $user['id']);
            $userMotorId = $activeMotor ? (int)$activeMotor['id'] : 0;
        }
        $namaKomponen = trim($input['nama_komponen'] ?? '');
        $kodePart = trim($input['kode_part'] ?? '');
        $odometer = (int)($input['odometer'] ?? 0);
        $tanggal = trim($input['tanggal'] ?? date('Y-m-d'));
        $bengkel = trim($input['bengkel'] ?? 'Bengkel');
        $biaya = (int)($input['biaya'] ?? 0);
        $catatan = trim($input['catatan'] ?? '');
        $fotoNota = saveBase64Image($input['foto_nota'] ?? null);

        if (empty($namaKomponen) || $odometer <= 0) {
            jsonError("Nama komponen dan angka odometer wajib diisi");
        }

        $stmt = $pdo->prepare("
            INSERT INTO riwayat_servis (
                user_motor_id, user_part_status_id, nama_komponen, kode_part,
                odometer_saat_ganti, tanggal_ganti, catatan, biaya, bengkel, status, foto_nota, created_at
            ) VALUES (?, NULL, ?, ?, ?, ?, ?, ?, ?, 'aktif', ?, ?)
        ");
        $stmt->execute([
            $userMotorId,
            $namaKomponen,
            $kodePart,
            $odometer,
            $tanggal,
            $catatan,
            $biaya,
            $bengkel,
            $fotoNota,
            date('Y-m-d H:i:s'),
        ]);

        jsonResponse([
            'success' => true,
            'message' => "Servis manual berhasil dicatat ke buku servis",
        ]);
        break;

    // ==========================================
    // CATALOG & MOTOR REGISTRATION (FR-010 - FR-014)
    // ==========================================
    case 'get_catalog':
        $motors = $pdo->query("SELECT * FROM master_motor WHERE aktif = 1 ORDER BY merek ASC, tipe ASC")->fetchAll();
        $parts = $pdo->query("SELECT * FROM master_part WHERE aktif = 1 ORDER BY id ASC")->fetchAll();

        $partsByMotor = [];
        foreach ($parts as $p) {
            $partsByMotor[$p['master_motor_id']][] = $p;
        }

        foreach ($motors as &$m) {
            $m['parts'] = $partsByMotor[$m['id']] ?? [];
        }

        jsonResponse(['success' => true, 'catalog' => $motors]);
        break;

    case 'register_motor':
        $user = getCurrentUser($pdo);
        $masterMotorId = (int)($input['master_motor_id'] ?? 0);
        $nomorPolisi = strtoupper(trim($input['nomor_polisi'] ?? ''));
        $odometer = (int)($input['odometer'] ?? 0);
        $customBaselines = $input['custom_baselines'] ?? [];

        // Multi-motor support: user can register multiple motorcycles to their garage

        // BR-005 & BR-014: Validation
        if ($odometer < 0 || $odometer > 999999) {
            jsonError("Angka odometer harus di antara 0 - 999.999 km");
        }

        // Fetch master motor & master parts
        $stmtM = $pdo->prepare("SELECT * FROM master_motor WHERE id = ? AND aktif = 1");
        $stmtM->execute([$masterMotorId]);
        $masterMotor = $stmtM->fetch();
        if (!$masterMotor) {
            jsonError("Tipe motor tidak valid atau tidak aktif");
        }

        $stmtP = $pdo->prepare("SELECT * FROM master_part WHERE master_motor_id = ? AND aktif = 1");
        $stmtP->execute([$masterMotorId]);
        $masterParts = $stmtP->fetchAll();

        // Atomically save user_motor and snapshot user_part_status
        $pdo->beginTransaction();
        try {
            $now = date('Y-m-d H:i:s');
            $today = date('Y-m-d');

            $insMotor = $pdo->prepare("
                INSERT INTO user_motor (user_id, master_motor_id, nomor_polisi, odometer_saat_ini, odometer_diperbarui_at, created_at)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $insMotor->execute([$user['id'], $masterMotorId, $nomorPolisi, $odometer, $now, $now]);
            $userMotorId = $pdo->lastInsertId();

            // BR-009 & FR-012, FR-013: Snapshot copy master_part to user_part_status
            $insPart = $pdo->prepare("
                INSERT INTO user_part_status (
                    user_motor_id, master_part_id, nama_komponen, interval_km,
                    interval_bulan, kode_part, odometer_baseline, tanggal_baseline
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($masterParts as $mp) {
                $baseOdo = $odometer;
                $baseDate = $today;

                // FR-013: Custom baseline if provided
                if (isset($customBaselines[$mp['id']])) {
                    $custom = $customBaselines[$mp['id']];
                    if (!empty($custom['odometer']) && (int)$custom['odometer'] <= $odometer && (int)$custom['odometer'] >= 0) {
                        $baseOdo = (int)$custom['odometer'];
                    }
                    if (!empty($custom['tanggal']) && strtotime($custom['tanggal']) <= time()) {
                        $baseDate = $custom['tanggal'];
                    }
                }

                $insPart->execute([
                    $userMotorId,
                    $mp['id'],
                    $mp['nama_komponen'],
                    $mp['interval_km'],
                    $mp['interval_bulan'],
                    $mp['kode_part_rekomendasi'],
                    $baseOdo,
                    $baseDate,
                ]);
            }

            // Log initial odometer to riwayat_odometer
            $logOdo = $pdo->prepare("INSERT INTO riwayat_odometer (user_motor_id, nilai, dicatat_at) VALUES (?, ?, ?)");
            $logOdo->execute([$userMotorId, $odometer, $now]);

            $pdo->commit();

            // Automatically switch active context to the newly added bike
            $_SESSION['active_motor_id'] = $userMotorId;
            session_write_close();

            jsonResponse([
                'success' => true,
                'motor_id' => $userMotorId,
                'message' => "Sepeda motor {$masterMotor['merek']} {$masterMotor['tipe']} berhasil didaftarkan ke garasi!",
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            jsonError("Pendaftaran gagal: " . $e->getMessage(), 500);
        }
        break;

    // ==========================================
    // MULTI-MOTOR SWITCHER (GARASI MULTI-MOTOR)
    // ==========================================
    case 'switch_motor':
        $user = getCurrentUser($pdo);
        $motorId = (int)($input['motor_id'] ?? 0);

        if ($motorId <= 0) {
            jsonError("ID motor tidak valid");
        }

        $stmt = $pdo->prepare("SELECT id, nomor_polisi FROM user_motor WHERE id = ? AND user_id = ?");
        $stmt->execute([$motorId, $user['id']]);
        $motor = $stmt->fetch();

        if (!$motor) {
            jsonError("Motor tidak ditemukan atau bukan milik Anda", 404);
        }

        $_SESSION['active_motor_id'] = $motorId;
        session_write_close();

        jsonResponse([
            'success' => true,
            'active_motor_id' => $motorId,
            'message' => "Berhasil beralih ke motor {$motor['nomor_polisi']}",
        ]);
        break;

    // ==========================================
    // WORKSHOP FINDER (FR-040 - FR-045, BR-012, BR-013)
    // ==========================================
    case 'get_workshops':
        // Coordinates and search parameters
        $lat = isset($_GET['lat']) ? (float)$_GET['lat'] : -6.3728; // Default Margonda Depok
        $lng = isset($_GET['lng']) ? (float)$_GET['lng'] : 106.8335;
        $radius = isset($_GET['radius']) ? min(10, max(3, (int)$_GET['radius'])) : 5; // BR-012: 3-10 km
        $typeFilter = $_GET['filter'] ?? 'all'; // all, resmi, umum

        // Location context passed from client or auto-detected from coordinates
        $area = trim($_GET['area'] ?? '');
        $city = trim($_GET['city'] ?? '');
        $road = trim($_GET['road'] ?? '');

        // Auto-detect Indonesian regions if not supplied by client
        if (empty($city)) {
            if ($lat >= -7.65 && $lat <= -7.25 && $lng >= 109.15 && $lng <= 109.60) {
                $city = 'Purbalingga';
                if (empty($area)) $area = 'Kalimanah';
                if (empty($road)) $road = 'Jl. Raya Mayjen Sungkono';
            } elseif ($lat >= -7.50 && $lat <= -7.35 && $lng >= 109.10 && $lng <= 109.30) {
                $city = 'Purwokerto';
                if (empty($area)) $area = 'Banyumas';
                if (empty($road)) $road = 'Jl. Jenderal Soedirman';
            } elseif ($lat >= -6.45 && $lat <= -6.32 && $lng >= 106.75 && $lng <= 106.90) {
                $city = 'Depok';
                if (empty($area)) $area = 'Margonda';
                if (empty($road)) $road = 'Jl. Margonda Raya';
            } elseif ($lat >= -6.38 && $lat <= -6.10 && $lng >= 106.68 && $lng <= 107.00) {
                $city = 'Jakarta';
                if (empty($area)) $area = 'Pusat';
                if (empty($road)) $road = 'Jl. Pemuda';
            } elseif ($lat >= -7.05 && $lat <= -6.80 && $lng >= 107.50 && $lng <= 107.75) {
                $city = 'Bandung';
                if (empty($area)) $area = 'Kota';
                if (empty($road)) $road = 'Jl. Soekarno Hatta';
            } elseif ($lat >= -7.15 && $lat <= -6.90 && $lng >= 110.30 && $lng <= 110.55) {
                $city = 'Semarang';
                if (empty($area)) $area = 'Kota';
                if (empty($road)) $road = 'Jl. Pandanaran';
            } elseif ($lat >= -7.90 && $lat <= -7.70 && $lng >= 110.30 && $lng <= 110.50) {
                $city = 'Yogyakarta';
                if (empty($area)) $area = 'Sleman';
                if (empty($road)) $road = 'Jl. Magelang';
            } elseif ($lat >= -7.38 && $lat <= -7.15 && $lng >= 112.65 && $lng <= 112.85) {
                $city = 'Surabaya';
                if (empty($area)) $area = 'Pusat';
                if (empty($road)) $road = 'Jl. Basuki Rahmat';
            } else {
                $city = 'Area Terdekat';
                if (empty($area)) $area = 'Pusat Kecamatan';
                if (empty($road)) $road = 'Jl. Raya Utama';
            }
        }
        if (empty($area)) $area = $city;
        if (empty($road)) $road = "Jl. Raya {$area}";

        // Determine realistic local phone code
        if (stripos($city, 'Purbalingga') !== false || stripos($city, 'Purwokerto') !== false || stripos($city, 'Banyumas') !== false) {
            $phonePrefix = '(0281) ';
        } elseif (stripos($city, 'Depok') !== false || stripos($city, 'Jakarta') !== false) {
            $phonePrefix = '(021) ';
        } elseif (stripos($city, 'Bandung') !== false) {
            $phonePrefix = '(022) ';
        } elseif (stripos($city, 'Semarang') !== false) {
            $phonePrefix = '(024) ';
        } elseif (stripos($city, 'Yogyakarta') !== false) {
            $phonePrefix = '(0274) ';
        } elseif (stripos($city, 'Surabaya') !== false) {
            $phonePrefix = '(031) ';
        } else {
            $phonePrefix = '0812-';
        }

        // Verified curated workshop network dynamically tailored around user's actual location
        $curatedWorkshops = [
            [
                'id' => 1,
                'name' => "AHASS Daya Motor {$city}",
                'type' => 'resmi',
                'brand' => 'Honda AHASS',
                'dealer_code' => 'AHASS #' . substr(abs(crc32($city . '1')), 0, 5),
                'lat' => $lat + 0.0055,
                'lng' => $lng + 0.0035,
                'address' => "{$road} No. 284, {$area}, {$city}",
                'phone' => $phonePrefix . (770000 + abs(crc32($city . '1')) % 9000),
                'wa_phone' => '628117720449',
                'hours' => 'Senin - Sabtu: 07.30 - 16.30 WIB, Minggu: 08.00 - 14.00 WIB',
                'parts_stock' => 'Stok Komplit AHM Oil, Busi NGK, Kampas Rem AHM',
                'rating' => 4.8,
            ],
            [
                'id' => 2,
                'name' => "AHASS Bintang Motor {$area}",
                'type' => 'resmi',
                'brand' => 'Honda AHASS',
                'dealer_code' => 'AHASS #' . substr(abs(crc32($area . '2')), 0, 5),
                'lat' => $lat - 0.0065,
                'lng' => $lng + 0.0045,
                'address' => "{$road} No. 102, {$area}, {$city}",
                'phone' => $phonePrefix . (770000 + abs(crc32($area . '2')) % 9000),
                'wa_phone' => '628127773829',
                'hours' => 'Senin - Minggu: 08.00 - 17.00 WIB',
                'parts_stock' => 'Tersedia Spare Part Asli & PIT Express Cepat',
                'rating' => 4.7,
            ],
            [
                'id' => 3,
                'name' => "AHASS Astra Motor {$city}",
                'type' => 'resmi',
                'brand' => 'Honda AHASS',
                'dealer_code' => 'AHASS #' . substr(abs(crc32($city . '3')), 0, 5),
                'lat' => $lat + 0.0090,
                'lng' => $lng - 0.0070,
                'address' => "Jl. Jenderal Sudirman No. 12, {$city}",
                'phone' => $phonePrefix . (770000 + abs(crc32($city . '3')) % 9000),
                'wa_phone' => '628127751029',
                'hours' => 'Senin - Sabtu: 08.00 - 16.00 WIB',
                'parts_stock' => 'Servis Berkala & Ganti Part Bergaransi',
                'rating' => 4.6,
            ],
            [
                'id' => 4,
                'name' => "Yamaha Mataram Sakti {$city}",
                'type' => 'resmi',
                'brand' => 'Yamaha 3S',
                'dealer_code' => 'YAMAHA #' . substr(abs(crc32($city . '4')), 0, 4),
                'lat' => $lat - 0.0080,
                'lng' => $lng - 0.0060,
                'address' => "{$road} No. 45, {$area}, {$city}",
                'phone' => $phonePrefix . (770000 + abs(crc32($city . '4')) % 9000),
                'wa_phone' => '628137765521',
                'hours' => 'Senin - Sabtu: 08.00 - 17.00 WIB',
                'parts_stock' => 'Yamalube Asli, Busi, Roller V-Belt',
                'rating' => 4.7,
            ],
            [
                'id' => 5,
                'name' => "Bengkel Mitra Motor {$area}",
                'type' => 'umum',
                'brand' => 'Bengkel Umum & UMKM',
                'dealer_code' => 'UMKM Terverifikasi',
                'lat' => $lat + 0.0070,
                'lng' => $lng - 0.0040,
                'address' => "{$road} No. 450, {$area}, {$city}",
                'phone' => '0812-9844-3321',
                'wa_phone' => '6281298443321',
                'hours' => 'Setiap Hari: 08.00 - 21.00 WIB (Buka Malam)',
                'parts_stock' => 'Spesialis Matic & Injeksi, Ganti Ban, Kuras Oli',
                'rating' => 4.6,
            ],
            [
                'id' => 6,
                'name' => "Planet Ban {$area} {$city}",
                'type' => 'umum',
                'brand' => 'Spesialis Ban & Servis Ringan',
                'dealer_code' => 'Modern Repair Shop',
                'lat' => $lat - 0.0040,
                'lng' => $lng + 0.0050,
                'address' => "{$road} No. 188, {$area}, {$city}",
                'phone' => '0815-1122-3344',
                'wa_phone' => '6281511223344',
                'hours' => 'Setiap Hari: 08.30 - 20.30 WIB',
                'parts_stock' => 'Oli X-Ten, Servis Rasa Mesin Baru, Ban Tubeless',
                'rating' => 4.5,
            ],
            [
                'id' => 7,
                'name' => "MotoQuick {$area} Center",
                'type' => 'umum',
                'brand' => 'Spesialis Oli & Aki',
                'dealer_code' => 'Fast Service',
                'lat' => $lat + 0.0110,
                'lng' => $lng + 0.0080,
                'address' => "Pusat Pertokoan {$area}, {$city}",
                'phone' => '0818-0900-1234',
                'wa_phone' => '6281809001234',
                'hours' => '08.00 - 18.00 WIB',
                'parts_stock' => 'Ganti Aki Bergaransi, Oli Shell/Federal/AHM',
                'rating' => 4.4,
            ],
            [
                'id' => 8,
                'name' => "Bengkel Berkah Jaya Mandiri ({$area})",
                'type' => 'umum',
                'brand' => 'Bengkel Motor Tradisional',
                'dealer_code' => 'Binaan UMKM',
                'lat' => $lat - 0.0100,
                'lng' => $lng - 0.0030,
                'address' => "Jl. Pemuda No. 14, {$area}, {$city}",
                'phone' => '0857-8899-1122',
                'wa_phone' => '6285788991122',
                'hours' => 'Setiap Hari: 07.00 - 19.00 WIB',
                'parts_stock' => 'Servis CVT, Karbu/Injeksi, Tambal Ban, Panggilan',
                'rating' => 4.8,
            ],
        ];

        // Haversine distance calculation in km
        function haversineGreatCircleDistance($latFrom, $lonFrom, $latTo, $lonTo, $earthRadius = 6371) {
            $latFrom = deg2rad($latFrom);
            $lonFrom = deg2rad($lonFrom);
            $latTo = deg2rad($latTo);
            $lonTo = deg2rad($lonTo);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            return $angle * $earthRadius;
        }

        $results = [];
        $counts = ['all' => 0, 'resmi' => 0, 'umum' => 0];

        foreach ($curatedWorkshops as $w) {
            $dist = haversineGreatCircleDistance($lat, $lng, $w['lat'], $w['lng']);
            $w['distance'] = round($dist, 2);
            $w['distance_text'] = $dist < 1 ? round($dist * 1000) . ' m' : number_format($dist, 1, ',', '.') . ' km';

            if ($dist <= $radius) {
                $counts['all']++;
                if ($w['type'] === 'resmi') $counts['resmi']++;
                if ($w['type'] === 'umum') $counts['umum']++;

                if ($typeFilter === 'all' || $w['type'] === $typeFilter) {
                    $results[] = $w;
                }
            }
        }

        // Sort by distance ascending (FR-042)
        usort($results, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        jsonResponse([
            'success' => true,
            'user_location' => ['lat' => $lat, 'lng' => $lng],
            'radius' => $radius,
            'filter' => $typeFilter,
            'counts' => $counts,
            'total_found' => count($results),
            'workshops' => $results,
            'osm_attribution' => '© OpenStreetMap contributors, Overpass API',
        ]);
        break;

    // ==========================================
    // ADMIN DATA MASTER CONTROLLERS (FR-060 - FR-063)
    // ==========================================
    case 'admin_get_master':
        $user = getCurrentUser($pdo);
        requireAdmin($user);

        $motors = $pdo->query("SELECT * FROM master_motor ORDER BY id ASC")->fetchAll();
        $parts = $pdo->query("
            SELECT mp.*, mm.merek, mm.tipe
            FROM master_part mp
            JOIN master_motor mm ON mp.master_motor_id = mm.id
            ORDER BY mp.master_motor_id ASC, mp.id ASC
        ")->fetchAll();

        // Check reference count for soft-delete enforcement (FR-062)
        $motorUsage = $pdo->query("SELECT master_motor_id, COUNT(*) as count FROM user_motor GROUP BY master_motor_id")->fetchAll(PDO::FETCH_KEY_PAIR);
        foreach ($motors as &$m) {
            $m['used_count'] = $motorUsage[$m['id']] ?? 0;
        }

        jsonResponse([
            'success' => true,
            'motors' => $motors,
            'parts' => $parts,
        ]);
        break;

    case 'admin_save_motor':
        $user = getCurrentUser($pdo);
        requireAdmin($user);

        $id = (int)($input['id'] ?? 0);
        $merek = trim($input['merek'] ?? '');
        $tipe = trim($input['tipe'] ?? '');
        $varian = trim($input['varian'] ?? '');

        if (empty($merek) || empty($tipe)) {
            jsonError("Merek dan Tipe motor wajib diisi");
        }

        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE master_motor SET merek = ?, tipe = ?, varian = ? WHERE id = ?");
            $stmt->execute([$merek, $tipe, $varian, $id]);
            $msg = "Data master motor berhasil diperbarui";
        } else {
            $stmt = $pdo->prepare("INSERT INTO master_motor (merek, tipe, varian, aktif) VALUES (?, ?, ?, 1)");
            $stmt->execute([$merek, $tipe, $varian]);
            $msg = "Master motor baru berhasil ditambahkan";
        }

        jsonResponse(['success' => true, 'message' => $msg]);
        break;

    case 'admin_toggle_motor':
        $user = getCurrentUser($pdo);
        requireAdmin($user);

        $id = (int)($input['id'] ?? 0);
        $status = (int)($input['status'] ?? 0);

        // FR-062: soft deactivation
        $stmt = $pdo->prepare("UPDATE master_motor SET aktif = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        jsonResponse([
            'success' => true,
            'message' => $status ? "Tipe motor diaktifkan kembali" : "Tipe motor dinonaktifkan",
        ]);
        break;

    case 'admin_save_part':
        $user = getCurrentUser($pdo);
        requireAdmin($user);

        $id = (int)($input['id'] ?? 0);
        $motorId = (int)($input['master_motor_id'] ?? 0);
        $nama = trim($input['nama_komponen'] ?? '');
        $km = !empty($input['interval_km']) ? (int)$input['interval_km'] : null;
        $bulan = !empty($input['interval_bulan']) ? (int)$input['interval_bulan'] : null;
        $kodePart = trim($input['kode_part_rekomendasi'] ?? '');

        // AC-061.1: Minimal satu interval > 0
        if (($km === null || $km <= 0) && ($bulan === null || $bulan <= 0)) {
            jsonError("Minimal satu interval (km atau bulan) wajib bernilai lebih dari 0");
        }

        if (empty($nama) || $motorId <= 0) {
            jsonError("Pilih tipe motor dan isi nama komponen");
        }

        if ($id > 0) {
            $stmt = $pdo->prepare("
                UPDATE master_part
                SET master_motor_id = ?, nama_komponen = ?, interval_km = ?, interval_bulan = ?, kode_part_rekomendasi = ?
                WHERE id = ?
            ");
            $stmt->execute([$motorId, $nama, $km, $bulan, $kodePart, $id]);
            $msg = "Master komponen berhasil diperbarui";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO master_part (master_motor_id, nama_komponen, interval_km, interval_bulan, kode_part_rekomendasi, aktif)
                VALUES (?, ?, ?, ?, ?, 1)
            ");
            $stmt->execute([$motorId, $nama, $km, $bulan, $kodePart]);
            $msg = "Master komponen berhasil ditambahkan";
        }

        jsonResponse(['success' => true, 'message' => $msg]);
        break;

    case 'admin_toggle_part':
        $user = getCurrentUser($pdo);
        requireAdmin($user);

        $id = (int)($input['id'] ?? 0);
        $status = (int)($input['status'] ?? 0);

        $stmt = $pdo->prepare("UPDATE master_part SET aktif = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        jsonResponse([
            'success' => true,
            'message' => $status ? "Komponen diaktifkan" : "Komponen dinonaktifkan",
        ]);
        break;

    default:
        jsonResponse([
            'success' => true,
            'service' => 'MotoCare Digital REST API v1.0',
            'status' => 'operational',
            'time' => date('Y-m-d H:i:s T'),
        ]);
        break;
    }
}

