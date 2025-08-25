<?php
// FILE: controllers/clients.php

/**
 * This controller handles the logic for displaying the clients list
 * based on different filter types.
 */

// 1. BOOTSTRAPPING & DEPENDENCIES
// =============================================================================
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../permissions_check.php';
require_once __DIR__ . '/../safe_output.php';

$pageTitle = __('clients');

// 2. HEADER
// =============================================================================
// Included early to handle potential permission-denied rendering.
include_once __DIR__ . '/../layout/header.php';

// 3. PERMISSION CHECK
// =============================================================================
if ($row_permcheck['clients_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">' . __('no_permission_to_view') . '</div></div>';
    include_once __DIR__ . '/../layout/footer.php';
    exit();
}

// 4. BUSINESS LOGIC
// =============================================================================
// Determine filter type from URL, default to 'all'.
$type = $_GET['type'] ?? 'all';
$params = [];
$types = '';

// Build SQL query based on the filter.
$sql = "SELECT id, arname, client_type, client_kind, tel1, email FROM client WHERE client_kind != '' AND arname != ''";
if ($type === 'clients') {
    $sql .= " AND client_kind = ?";
    $params[] = 'موكل'; // These might need to be translated if stored differently in DB
    $types .= 's';
} elseif ($type === 'opponents') {
    $sql .= " AND client_kind = ?";
    $params[] = 'خصم'; // These might need to be translated if stored differently in DB
    $types .= 's';
} elseif ($type === 'subs') {
    $sql .= " AND client_kind = ?";
    $params[] = 'عناوين هامة'; // These might need to be translated if stored differently in DB
    $types .= 's';
}
$sql .= " ORDER BY id DESC";

// Fetch clients using a secure method.
$clients = App\Database::select($sql, $params, $types);

// 5. RENDER VIEW
// =============================================================================
// Pass data to the corresponding view file.
render('clients.view.php', [
    'clients' => $clients,
    'row_permcheck' => $row_permcheck,
    'type' => $type
]);

// 6. FOOTER
// =============================================================================
include_once __DIR__ . '/../layout/footer.php';
