<?php
// FILE: FeesAgreement.php

/**
 * Generates a printable, dynamic fees agreement document for a client.
 *
 * This script fetches client details to populate the header of the document.
 * The main content is a table that can be dynamically expanded by the user.
 * JavaScript handles adding new rows and creating page breaks for printing.
 *
 * GET Params:
 * - cid: The Client ID (required, integer).
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';

// 2. PERMISSIONS & INPUT VALIDATION
// =============================================================================
// Check if the user has permission to read or print sensitive documents.
if ($row_permcheck['sdocs_rperm'] != 1) {
    http_response_code(403);
    die('Access Denied: You do not have permission to view this document.');
}

// Validate the client ID.
$clientId = isset($_GET['cid']) ? (int)$_GET['cid'] : 0;
if ($clientId <= 0) {
    http_response_code(400);
    die('Invalid Client ID provided.');
}

// 3. DATA FETCHING
// =============================================================================
// Fetch client details.
$stmt = $conn->prepare("SELECT arname, engname FROM client WHERE id = ?");
$stmt->bind_param("i", $clientId);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();
$stmt->close();

if (!$client) {
    http_response_code(404);
    die('Client not found.');
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إتفاقية الأتعاب - <?php echo safe_output($client['arname']); ?></title>
    <link href="css/fees_agreement_styles.css" rel="stylesheet">
</head>
<body>

    <?php if ($row_permcheck['sdocs_pperm'] == 1): ?>
        <div class="no-print controls">
            <button onclick="window.print();">
                <i class='bx bx-printer'></i> طباعة
            </button>
            <button id="addRowBtn">
                <i class='bx bx-plus'></i> إضافة صف
            </button>
        </div>
    <?php endif; ?>

    <div class="page" id="page-1">
        <header class="document-header">
            <div class="header-title-en">
                <h3>Fees Agreement</h3>
            </div>
            <div class="header-title-ar">
                <h3>إتفاقية الأتعاب</h3>
            </div>
        </header>
        
        <table class="fees-table" id="fees-table-1">
            <thead>
                <tr>
                    <th>
                        Client: <span class="client-name"><?php echo safe_output($client['engname']); ?></span>
                    </th>
                    <th>
                        الموكل: <span class="client-name"><?php echo safe_output($client['arname']); ?></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <!-- Initial row -->
                <tr>
                    <td><textarea rows="1" class="editable-cell"></textarea></td>
                    <td><textarea rows="1" class="editable-cell"></textarea></td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="js/fees_agreement_script.js"></script>
</body>
</html>
