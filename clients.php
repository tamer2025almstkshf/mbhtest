<?php
$pageTitle = 'العملاء';

// --- DEPENDENCIES ---
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/view.php';
require_once __DIR__ . '/login_check.php';
require_once __DIR__ . '/permissions_check.php';
require_once __DIR__ . '/safe_output.php';

// --- HEADER ---
// We include the header here because the permission check might need to stop execution
// and render a different view (the error message).
include_once 'layout/header.php';

// --- PERMISSION CHECK ---
if ($row_permcheck['clients_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// --- LOGIC ---
// Get the filter type from the URL, default to 'all'.
$type = $_GET['type'] ?? 'all';
$params = [];
$types = '';

// Build the SQL query based on the filter type.
$sql = "SELECT id, arname, client_type, client_kind, tel1, email FROM client WHERE client_kind != '' AND arname != ''";
if ($type === 'clients') {
    $sql .= " AND client_kind = ?";
    $params[] = 'موكل';
    $types .= 's';
} elseif ($type === 'opponents') {
    $sql .= " AND client_kind = ?";
    $params[] = 'خصم';
    $types .= 's';
} elseif ($type === 'subs') {
    $sql .= " AND client_kind = ?";
    $params[] = 'عناوين هامة';
    $types .= 's';
}
$sql .= " ORDER BY id DESC";

// Fetch the clients from the database using our secure Database class.
$clients = Database::select($sql, $params, $types);

// --- RENDER VIEW ---
// Pass the necessary data to the view.
render('clients.view.php', [
    'clients' => $clients,
    'row_permcheck' => $row_permcheck,
    'type' => $type
]);

// --- FOOTER ---
include_once 'layout/footer.php';
