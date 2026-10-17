<?php
/**
 * MotoCare Digital - Front Controller & Backend Server Router
 *
 * Arsitektur Terpisah:
 * - Partials:
 *   - partials/head.php    (HTML Head, Tailwind Config, Leaflet CSS & JS, html2pdf)
 *   - partials/header.php  (Top Header, Navigation, Garage Switcher, Mobile Nav)
 *   - partials/modals.php  (Dialog Reset Komponen, Odometer, Servis Manual, Admin Part, Auth, Toast, Bukti Nota)
 *   - partials/footer.php  (Footer, Script App, Penutup HTML)
 * - Views:
 *   - views/dashboard.php    (VIEW 1: Dashboard Servis)
 *   - views/bengkel.php      (VIEW 2: Cari Bengkel Terdekat)
 *   - views/riwayat.php      (VIEW 3: Riwayat Servis)
 *   - views/buku-servis.php  (VIEW 4: Buku Servis Digital PDF)
 *   - views/daftar-motor.php (VIEW 5: Pendaftaran Motor Baru)
 *   - views/admin.php        (VIEW 6: Admin Data Master)
 * - Backend API Router:
 *   - api/index.php          (REST API Router & Business Logic)
 *
 * Routing Features:
 * 1. Routing API ke Server Backend:
 *    - Delegasi index.php?action=... atau index.php?api=... langsung ke backend api/index.php.
 * 2. Routing Halaman / View Server:
 *    - Mendukung index.php?page=... (dashboard, bengkel, riwayat, buku-servis, daftar-motor, admin).
 * 3. Routing Komponen View / Partial Langsung (AJAX / Micro-frontend):
 *    - index.php?view_only=... atau index.php?partial=...
 * 4. Multi-Page & SPA Hybrid:
 *    - Sinkronisasi instan antara rute server PHP dan rute klien JavaScript.
 */

// 1. ROUTING API KE SERVER BACKEND
// Jika parameter action atau api dikirim ke index.php, delegasikan ke controller backend API
if (isset($_GET['action']) || isset($_GET['api'])) {
    if (isset($_GET['api']) && !isset($_GET['action'])) {
        $_GET['action'] = $_GET['api'];
    }
    require_once __DIR__ . '/api/index.php';
    exit;
}

// 2. DETEKSI BASE URI DAN REDIRECT KE CLEAN URL
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$baseUri = rtrim(dirname($scriptName), '/\\') . '/';
if ($baseUri === '//') $baseUri = '/';

// Jika pengguna mengakses langsung ke /motocare/ atau /motocare/index.php tanpa query string,
// redirect langsung ke clean URL /motocare/dashboard
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
if (!isset($_GET['page']) && !isset($_GET['action']) && !isset($_GET['api']) && !isset($_GET['partial']) && !isset($_GET['view_only']) && !isset($_GET['ajax'])) {
    if ($requestPath === $baseUri || $requestPath === $baseUri . 'index.php') {
        header("Location: " . $baseUri . "dashboard", true, 302);
        exit;
    }
}

// 3. DAFTAR VIEW DAN PARTIAL TERSEDIA
$views = [
    'dashboard'       => ['file' => __DIR__ . '/views/dashboard.php',    'title' => 'Dashboard Servis',           'tab' => 'dashboard',    'slug' => 'dashboard'],
    'peta-bengkel'    => ['file' => __DIR__ . '/views/bengkel.php',      'title' => 'Cari Bengkel Terdekat',     'tab' => 'peta-bengkel', 'slug' => 'bengkel'],
    'bengkel'         => ['file' => __DIR__ . '/views/bengkel.php',      'title' => 'Cari Bengkel Terdekat',     'tab' => 'peta-bengkel', 'slug' => 'bengkel'],
    'riwayat-servis'  => ['file' => __DIR__ . '/views/riwayat.php',      'title' => 'Riwayat Servis Motor',      'tab' => 'riwayat-servis','slug' => 'riwayat'],
    'riwayat'         => ['file' => __DIR__ . '/views/riwayat.php',      'title' => 'Riwayat Servis Motor',      'tab' => 'riwayat-servis','slug' => 'riwayat'],
    'buku-servis'     => ['file' => __DIR__ . '/views/buku-servis.php',  'title' => 'Buku Servis Digital PDF',   'tab' => 'buku-servis',  'slug' => 'buku-servis'],
    'pdf'             => ['file' => __DIR__ . '/views/buku-servis.php',  'title' => 'Buku Servis Digital PDF',   'tab' => 'buku-servis',  'slug' => 'buku-servis'],
    'daftar-motor'    => ['file' => __DIR__ . '/views/daftar-motor.php', 'title' => 'Pendaftaran Sepeda Motor',  'tab' => 'daftar-motor', 'slug' => 'daftar-motor'],
    'motor'           => ['file' => __DIR__ . '/views/daftar-motor.php', 'title' => 'Pendaftaran Sepeda Motor',  'tab' => 'daftar-motor', 'slug' => 'daftar-motor'],
    'admin'           => ['file' => __DIR__ . '/views/admin.php',        'title' => 'Pengelolaan Data Master',   'tab' => 'admin',        'slug' => 'admin'],
];

$partials = [
    'head'    => __DIR__ . '/partials/head.php',
    'header'  => __DIR__ . '/partials/header.php',
    'modals'  => __DIR__ . '/partials/modals.php',
    'footer'  => __DIR__ . '/partials/footer.php',
];

// Deteksi route yang diminta via query string (?page=...) atau path URI
$requestedPage = trim($_GET['page'] ?? $_GET['route'] ?? $_GET['view'] ?? '', '/');
if (empty($requestedPage) && isset($_SERVER['REQUEST_URI'])) {
    $uriPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $basePath = trim($baseUri, '/');
    if (!empty($basePath) && strpos($uriPath, $basePath) === 0) {
        $uriPath = trim(substr($uriPath, strlen($basePath)), '/');
    }
    if (!empty($uriPath) && $uriPath !== 'index.php') {
        $requestedPage = $uriPath;
    }
}

if (empty($requestedPage) || !isset($views[$requestedPage])) {
    $requestedPage = 'dashboard';
}

$activeTab = $views[$requestedPage]['tab'];
$activeSlug = $views[$requestedPage]['slug'];
$pageTitle = $views[$requestedPage]['title'] . ' — MotoCare Digital';

// 3. ROUTING VIEW LANGSUNG (AJAX / MICRO-FRONTEND COMPONENT)
if (isset($_GET['view_only']) || isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && isset($_GET['view']))) {
    if (file_exists($views[$requestedPage]['file'])) {
        require $views[$requestedPage]['file'];
        exit;
    }
    http_response_code(404);
    echo "View tidak ditemukan.";
    exit;
}

// 4. ROUTING PARTIAL LANGSUNG
if (isset($_GET['partial'])) {
    $partialKey = $_GET['partial'];
    if (isset($partials[$partialKey]) && file_exists($partials[$partialKey])) {
        require $partials[$partialKey];
        exit;
    }
    http_response_code(404);
    echo "Partial tidak ditemukan.";
    exit;
}

// 5. RENDER FULL APPLICATION LAYOUT (HYBRID SPA + SERVER ROUTED)
// Head partial (Meta, CSS Tailwind, Leaflet, Fonts, Dynamic Title)
require_once $partials['head'];

// Header partial (Top navigation, mobile bottom navigation, and body opening tag)
require_once $partials['header'];
?>

  <!-- ======================================================== -->
  <!-- MAIN ROUTE CONTAINER -->
  <!-- ======================================================== -->
  <main class="flex-1 w-full flex flex-col pt-16 pb-20 md:pb-8">
    <?php
    // Include all modular views
    require_once __DIR__ . '/views/dashboard.php';
    require_once __DIR__ . '/views/bengkel.php';
    require_once __DIR__ . '/views/riwayat.php';
    require_once __DIR__ . '/views/buku-servis.php';
    require_once __DIR__ . '/views/daftar-motor.php';
    require_once __DIR__ . '/views/admin.php';
    ?>
  </main>

  <script>
    // Sinkronisasi route server dengan frontend
    window.initialRoute = <?= json_encode($activeTab) ?>;
  </script>

<?php
// Modals partial (One-Click Reset, Odometer, Manual Service, Admin Part, Auth/User Switcher, Toast, Preview Nota)
require_once $partials['modals'];

// Footer partial (Footer info, assets/js/app.js script, and closing tags)
require_once $partials['footer'];
