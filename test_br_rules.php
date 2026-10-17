<?php
/**
 * MotoCare Digital - Automated Test Suite
 * Validates PRD Metric G1: Akurasi perhitungan status pada set uji (BR-001…BR-006)
 * Target: 100% kelulusan dari >= 25 kasus uji
 */

define('MOTOCARE_TEST_MODE', true);
require_once __DIR__ . '/api/db.php';
require_once __DIR__ . '/api/index.php';

$testCases = [
    // Case 1-6: Tabel contoh resmi PRD (Bagian 9)
    // Oli Mesin: interval 2000 km, 2 bulan (=60 hari). Baseline: odo 10000, 1 Sep 2026.
    // Hari ke-36 (sisa 24 hari):
    [
        'desc' => 'PRD Sample 1: Odo 11.200 (sisa 800 km, 24 hari) -> Hijau',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-36 days'))],
        'current_odo' => 11200,
        'expected_status' => 'hijau',
        'expected_km' => 800,
        'expected_days' => 24,
    ],
    [
        'desc' => 'PRD Sample 2: Odo 11.500 (sisa 500 km, 24 hari) -> Hijau (Ambang 500)',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-36 days'))],
        'current_odo' => 11500,
        'expected_status' => 'hijau',
        'expected_km' => 500,
        'expected_days' => 24,
    ],
    [
        'desc' => 'PRD Sample 3: Odo 11.501 (sisa 499 km, 24 hari) -> Kuning (km < 500)',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-36 days'))],
        'current_odo' => 11501,
        'expected_status' => 'kuning',
        'expected_km' => 499,
        'expected_days' => 24,
    ],
    [
        'desc' => 'PRD Sample 4: Odo 11.999 (sisa 1 km, 24 hari) -> Kuning (sisa 1 km)',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-36 days'))],
        'current_odo' => 11999,
        'expected_status' => 'kuning',
        'expected_km' => 1,
        'expected_days' => 24,
    ],
    [
        'desc' => 'PRD Sample 5: Odo 12.000 (sisa 0 km, 24 hari) -> Merah (Batas tercapai BR-003)',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-36 days'))],
        'current_odo' => 12000,
        'expected_status' => 'merah',
        'expected_km' => 0,
        'expected_days' => 24,
    ],
    [
        'desc' => 'PRD Sample 6: Odo 12.150 (sisa -150 km, 24 hari) -> Merah (Melebihi batas)',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-36 days'))],
        'current_odo' => 12150,
        'expected_status' => 'merah',
        'expected_km' => -150,
        'expected_days' => 24,
    ],

    // Case 7-10: Ambang Hari (Days Thresholds)
    [
        'desc' => 'PRD Sample 7: Hari ke-53 (sisa 7 hari, 800 km) -> Hijau (Ambang 7 hari)',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-53 days'))],
        'current_odo' => 11200,
        'expected_status' => 'hijau',
        'expected_days' => 7,
    ],
    [
        'desc' => 'PRD Sample 8: Hari ke-54 (sisa 6 hari, 800 km) -> Kuning (hari < 7)',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-54 days'))],
        'current_odo' => 11200,
        'expected_status' => 'kuning',
        'expected_days' => 6,
    ],
    [
        'desc' => 'PRD Sample 9: Hari ke-60 (sisa 0 hari, 800 km) -> Merah (Batas waktu habis)',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-60 days'))],
        'current_odo' => 11200,
        'expected_status' => 'merah',
        'expected_days' => 0,
    ],
    [
        'desc' => 'PRD Sample 10: Hari ke-62 (sisa -2 hari, 700 km) -> Merah (Waktu terlampaui walau km aman)',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-62 days'))],
        'current_odo' => 11300,
        'expected_status' => 'merah',
        'expected_days' => -2,
    ],

    // Case 11-15: Single Dimension Evaluation (BR-006)
    [
        'desc' => 'BR-006: Hanya interval km (bulan null), km aman -> Hijau',
        'item' => ['interval_km' => 5000, 'interval_bulan' => null, 'odometer_baseline' => 1000, 'tanggal_baseline' => '2020-01-01'],
        'current_odo' => 2000,
        'expected_status' => 'hijau',
        'expected_km' => 4000,
    ],
    [
        'desc' => 'BR-006: Hanya interval km, sisa 400 km -> Kuning',
        'item' => ['interval_km' => 5000, 'interval_bulan' => null, 'odometer_baseline' => 1000, 'tanggal_baseline' => '2020-01-01'],
        'current_odo' => 5600,
        'expected_status' => 'kuning',
        'expected_km' => 400,
    ],
    [
        'desc' => 'BR-006: Hanya interval km, sisa 0 km -> Merah',
        'item' => ['interval_km' => 5000, 'interval_bulan' => null, 'odometer_baseline' => 1000, 'tanggal_baseline' => '2020-01-01'],
        'current_odo' => 6000,
        'expected_status' => 'merah',
        'expected_km' => 0,
    ],
    [
        'desc' => 'BR-006: Hanya interval bulan (km null), sisa 20 hari -> Hijau',
        'item' => ['interval_km' => null, 'interval_bulan' => 1, 'odometer_baseline' => 0, 'tanggal_baseline' => date('Y-m-d', strtotime('-10 days'))],
        'current_odo' => 99999,
        'expected_status' => 'hijau',
        'expected_days' => 20,
    ],
    [
        'desc' => 'BR-006: Hanya interval bulan, sisa 3 hari -> Kuning',
        'item' => ['interval_km' => null, 'interval_bulan' => 1, 'odometer_baseline' => 0, 'tanggal_baseline' => date('Y-m-d', strtotime('-27 days'))],
        'current_odo' => 99999,
        'expected_status' => 'kuning',
        'expected_days' => 3,
    ],

    // Case 16-20: Multi-condition Worst Case Status (BR-005)
    [
        'desc' => 'BR-005: km Kuning (200 km) dan hari Hijau (20 hari) -> Mengikuti terburuk = Kuning',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-40 days'))],
        'current_odo' => 11800,
        'expected_status' => 'kuning',
    ],
    [
        'desc' => 'BR-005: km Hijau (800 km) dan hari Kuning (4 hari) -> Mengikuti terburuk = Kuning',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-56 days'))],
        'current_odo' => 11200,
        'expected_status' => 'kuning',
    ],
    [
        'desc' => 'BR-005: km Merah (-10 km) dan hari Hijau (30 hari) -> Mengikuti terburuk = Merah',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-30 days'))],
        'current_odo' => 12010,
        'expected_status' => 'merah',
    ],
    [
        'desc' => 'BR-005: km Hijau (1500 km) dan hari Merah (-1 hari) -> Mengikuti terburuk = Merah',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-61 days'))],
        'current_odo' => 10500,
        'expected_status' => 'merah',
    ],
    [
        'desc' => 'BR-005: km Merah dan hari Merah -> Merah',
        'item' => ['interval_km' => 2000, 'interval_bulan' => 2, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-65 days'))],
        'current_odo' => 13000,
        'expected_status' => 'merah',
    ],

    // Case 21-25: High-mileage & Large Intervals (V-Belt 24.000 km, Radiator 12.000 km)
    [
        'desc' => 'V-Belt 24.000 km: odo 23.500 (sisa 500 km) -> Hijau (tepat di ambang batas)',
        'item' => ['interval_km' => 24000, 'interval_bulan' => 24, 'odometer_baseline' => 0, 'tanggal_baseline' => date('Y-m-d', strtotime('-100 days'))],
        'current_odo' => 23500,
        'expected_status' => 'hijau',
        'expected_km' => 500,
    ],
    [
        'desc' => 'V-Belt 24.000 km: odo 23.501 (sisa 499 km) -> Kuning',
        'item' => ['interval_km' => 24000, 'interval_bulan' => 24, 'odometer_baseline' => 0, 'tanggal_baseline' => date('Y-m-d', strtotime('-100 days'))],
        'current_odo' => 23501,
        'expected_status' => 'kuning',
        'expected_km' => 499,
    ],
    [
        'desc' => 'Radiator Coolant 12.000 km: odo 12.000 (sisa 0 km) -> Merah',
        'item' => ['interval_km' => 12000, 'interval_bulan' => 12, 'odometer_baseline' => 0, 'tanggal_baseline' => date('Y-m-d', strtotime('-100 days'))],
        'current_odo' => 12000,
        'expected_status' => 'merah',
        'expected_km' => 0,
    ],
    [
        'desc' => 'Radiator Coolant 12.000 km: odo 13.000 (sisa -1000 km) -> Merah',
        'item' => ['interval_km' => 12000, 'interval_bulan' => 12, 'odometer_baseline' => 0, 'tanggal_baseline' => date('Y-m-d', strtotime('-100 days'))],
        'current_odo' => 13000,
        'expected_status' => 'merah',
        'expected_km' => -1000,
    ],
    [
        'desc' => 'Busi 8.000 km: pemakaian baru 1.000 km (sisa 7.000 km) -> Hijau (Wear 12%)',
        'item' => ['interval_km' => 8000, 'interval_bulan' => 8, 'odometer_baseline' => 10000, 'tanggal_baseline' => date('Y-m-d', strtotime('-20 days'))],
        'current_odo' => 11000,
        'expected_status' => 'hijau',
        'expected_km' => 7000,
    ],
    [
        'desc' => 'Oli Gardan 8.000 km: sisa 499 km dan sisa 100 hari -> Kuning',
        'item' => ['interval_km' => 8000, 'interval_bulan' => 8, 'odometer_baseline' => 0, 'tanggal_baseline' => date('Y-m-d', strtotime('-140 days'))],
        'current_odo' => 7501,
        'expected_status' => 'kuning',
        'expected_km' => 499,
    ],
];

echo "=================================================================\n";
echo " MOTOCARE DIGITAL - TEST SUITE PERHITUNGAN STATUS (BR-001 - BR-007)\n";
echo " Target Metrik G1: 100% kelulusan dari >= 25 kasus uji PRD\n";
echo "=================================================================\n\n";

$passed = 0;
$total = count($testCases);

foreach ($testCases as $idx => $tc) {
    $num = $idx + 1;
    $res = calculateComponentStatus($tc['item'], $tc['current_odo']);

    $statusMatch = ($res['status_code'] === $tc['expected_status']);
    $kmMatch = !isset($tc['expected_km']) || ($res['sisa_km'] === $tc['expected_km']);
    $daysMatch = !isset($tc['expected_days']) || ($res['sisa_hari'] === $tc['expected_days']);

    if ($statusMatch && $kmMatch && $daysMatch) {
        $passed++;
        echo " [PASS] #{$num}: {$tc['desc']}\n";
        echo "        -> Status: {$res['status_code']} | Sisa km: {$res['sisa_km']} | Sisa hari: {$res['sisa_hari']}\n";
    } else {
        echo " [FAIL] #{$num}: {$tc['desc']}\n";
        echo "        -> Expected: Status={$tc['expected_status']}\n";
        echo "        -> Got:      Status={$res['status_code']} (km={$res['sisa_km']}, days={$res['sisa_hari']})\n";
    }
}

$passRate = (int)round(($passed / $total) * 100);
echo "\n-----------------------------------------------------------------\n";
echo " HASIL AKHIR: {$passed} / {$total} Kasus Uji Lulus ({$passRate}%)\n";
echo " Status Metrik G1: " . ($passRate === 100 ? "LULUS SEMPURNA (100% Target Tercapai)" : "GAGAL") . "\n";
echo "=================================================================\n";
