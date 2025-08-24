<?php
// FILE: CasesFees.php

/**
 * Page for managing case fees.
 * Allows viewing, adding, and deleting fee records for cases.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
include_once 'connection.php';
include_once 'login_check.php';
include_once 'safe_output.php';
include_once 'permissions_check.php'; // This should set $row_permcheck

// 2. PERMISSIONS CHECK
// =============================================================================
if ($row_permcheck['acccasecost_rperm'] !== '1') {
    http_response_code(403);
    die('You do not have permission to access this page.');
}

// 3. DATA FETCHING & INITIALIZATION
// =============================================================================
$searchBy = $_GET['SearchBY'] ?? null;
$fileNumber = isset($_GET['Fno']) ? (int)$_GET['Fno'] : null;
$clientName = $_GET['Mname'] ?? null;

$pageData = [
    'files' => [],
    'selectedFileFees' => null,
    'allCaseFees' => []
];

// Fetch files based on search criteria
if ($searchBy && ($fileNumber || $clientName)) {
    if ($searchBy === 'FileNo' && $fileNumber) {
        $stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
        $stmt->bind_param("i", $fileNumber);
    } elseif ($searchBy === 'Cli' && $clientName) {
        $stmt = $conn->prepare("
            SELECT f.* FROM file f
            JOIN client c ON f.file_client = c.id
            WHERE c.arname = ? OR c.engname = ?
            ORDER BY f.file_id DESC
        ");
        $stmt->bind_param("ss", $clientName, $clientName);
    }

    if (isset($stmt)) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            // Fetch related data for each file
            $row['clients'] = getClientsForFile($conn, $row);
            $row['opponents'] = getOpponentsForFile($conn, $row);
            $row['latest_degree'] = getLatestDegreeForFile($conn, $row['file_id']);
            $pageData['files'][] = $row;
        }
        $stmt->close();
    }
}

// If a specific file is selected, get its fee details
if ($fileNumber && !empty($pageData['files'])) {
    $stmt = $conn->prepare("SELECT * FROM cases_fees WHERE fid = ?");
    $stmt->bind_param("i", $fileNumber);
    $stmt->execute();
    $pageData['selectedFileFees'] = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}


// Fetch all case fees for the main table
$result = $conn->query("SELECT * FROM cases_fees ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    $pageData['allCaseFees'][] = $row;
}

// 4. HELPER FUNCTIONS
// =============================================================================
function getClientsForFile($conn, $fileRow) {
    $clients = [];
    for ($i = 1; $i <= 5; $i++) {
        $clientIdKey = 'file_client' . ($i > 1 ? $i : '');
        $charKey = 'fclient_characteristic' . ($i > 1 ? $i : '');
        if (!empty($fileRow[$clientIdKey])) {
            $stmt = $conn->prepare("SELECT arname FROM client WHERE id = ?");
            $stmt->bind_param("i", $fileRow[$clientIdKey]);
            $stmt->execute();
            $client = $stmt->get_result()->fetch_assoc();
            $clients[] = safe_output($client['arname'] . ' / ' . $fileRow[$charKey]);
            $stmt->close();
        }
    }
    return $clients;
}

function getOpponentsForFile($conn, $fileRow) {
    // Similar to getClientsForFile, can be implemented if needed
    return [];
}

function getLatestDegreeForFile($conn, $fileId) {
    $stmt = $conn->prepare("SELECT * FROM file_degrees WHERE fid = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $degree = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $degree;
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <title>اتعاب القضايا</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Local CSS -->
    <link href="css/styles.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/favicon.ico">
</head>
<body style="overflow: auto">
    <div class="container">
        <?php include_once 'sidebar.php'; ?>
        <div class="website">
            <?php include_once 'header.php'; ?>
            <main class="web-page">
                <div class="table-container">
                    <header class="table-header">
                        <div class="table-header-right">
                            <h3>اتعاب القضايا</h3>
                        </div>
                        <div class="table-header-left">
                            <?php if ($row_permcheck['acccasecost_aperm'] === '1'): ?>
                                <button class="add-btn" onclick="openModal('addFeeModal')">
                                    <i class='bx bx-plus'></i> إضافة اتعاب
                                </button>
                            <?php endif; ?>
                        </div>
                    </header>

                    <div class="table-body">
                        <form action="deletecasecost.php" method="post" id="feesForm">
                            <table class="info-table" id="feesTable">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                                        <th>رقم الملف</th>
                                        <th>قيمة الاتعاب</th>
                                        <th>قيمة الاعمال الادارية</th>
                                        <th>نسبة التنبيه</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($pageData['allCaseFees'])): ?>
                                        <tr><td colspan="6">لا توجد بيانات لعرضها.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($pageData['allCaseFees'] as $fee): ?>
                                            <tr>
                                                <td><input type="checkbox" name="CheckedD[]" class="user-checkbox" value="<?php echo safe_output($fee['id']); ?>"></td>
                                                <td><?php echo safe_output($fee['fid']); ?></td>
                                                <td><?php echo safe_output($fee['fees']); ?> AED</td>
                                                <td><?php echo safe_output($fee['bm_fees']); ?> AED</td>
                                                <td><?php echo safe_output($fee['bm_alert']); ?>%</td>
                                                <td class="options-td">
                                                    <i class='bx bx-dots-vertical-rounded dropbtn' onclick="toggleDropdown(event)"></i>
                                                    <div class="dropdown">
                                                        <?php if ($row_permcheck['acccasecost_eperm'] === '1'): ?>
                                                            <!-- Edit functionality can be added here -->
                                                        <?php endif; ?>
                                                        <?php if ($row_permcheck['acccasecost_dperm'] === '1'): ?>
                                                            <a href="deletecasecost.php?id=<?php echo safe_output($fee['id']); ?>" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <footer class="table-footer">
                            <?php if ($row_permcheck['acccasecost_dperm'] === '1'): ?>
                                <button type="submit" name="delete_selected" class="delete-selected">حذف المحدد</button>
                            <?php endif; ?>
                        </footer>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <!-- Add/Edit Fee Modal -->
    <div id="addFeeModal" class="modal-overlay" style="display: <?php echo (isset($_GET['addmore'])) ? 'block' : 'none'; ?>">
        <div class="modal-content">
            <header class="modal-header">
                <h4>إضافة / تعديل اتعاب القضية</h4>
                <span class="close-button" onclick="closeModal('addFeeModal')">&times;</span>
            </header>
            <div class="modal-body">
                <!-- Search Form -->
                <form id="searchForm" method="get" action="CasesFees.php">
                    <input type="hidden" name="addmore" value="1">
                    <div class="form-group radio-group">
                        <label><input type="radio" name="SearchBY" value="FileNo" onchange="this.form.submit()" <?php if($searchBy === 'FileNo') echo 'checked'; ?>> رقم الملف</label>
                        <label><input type="radio" name="SearchBY" value="Cli" onchange="this.form.submit()" <?php if($searchBy === 'Cli') echo 'checked'; ?>> اسم الموكل</label>
                    </div>

                    <?php if ($searchBy === 'FileNo'): ?>
                    <div class="form-group">
                        <label for="Fno">رقم الملف</label>
                        <input type="number" class="form-input" id="Fno" name="Fno" value="<?php echo safe_output($fileNumber); ?>" onchange="this.form.submit()">
                    </div>
                    <?php elseif ($searchBy === 'Cli'): ?>
                    <div class="form-group">
                        <label for="Mname">اسم الموكل</label>
                        <input type="text" class="form-input" id="Mname" name="Mname" value="<?php echo safe_output($clientName); ?>" onchange="this.form.submit()">
                    </div>
                    <?php endif; ?>
                </form>

                <!-- File Results Table -->
                <?php if (!empty($pageData['files'])): ?>
                <div class="table-responsive" style="margin-top: 20px; max-height: 200px; overflow-y: auto;">
                    <table class="info-table results-table">
                        <thead>
                            <tr><th>رقم الملف</th><th>الموضوع</th><th>الموكل</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($pageData['files'] as $file): ?>
                            <tr onclick="location.href='?addmore=1&SearchBY=FileNo&Fno=<?php echo safe_output($file['file_id']); ?>'">
                                <td><?php echo safe_output($file['file_id']); ?></td>
                                <td><?php echo safe_output($file['file_subject']); ?></td>
                                <td><?php echo implode('<br>', $file['clients']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <!-- Fee Entry Form -->
                <?php if ($fileNumber && !empty($pageData['files'])): ?>
                <hr>
                <form action="cfees_process.php" method="post">
                    <input type="hidden" name="fid" value="<?php echo safe_output($fileNumber); ?>">
                    <div class="form-group">
                        <label for="fees">قيمة الاتعاب (AED)</label>
                        <input type="number" id="fees" name="fees" class="form-input" value="<?php echo safe_output($pageData['selectedFileFees']['fees'] ?? '0'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="bm_fees">قيمة الاعمال الإدارية (AED)</label>
                        <input type="number" id="bm_fees" name="bm_fees" class="form-input" value="<?php echo safe_output($pageData['selectedFileFees']['bm_fees'] ?? '0'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="bm_alert">نسبة التنبيه (%)</label>
                        <input type="number" id="bm_alert" name="bm_alert" class="form-input" value="<?php echo safe_output($pageData['selectedFileFees']['bm_alert'] ?? '0'); ?>" required>
                    </div>
                    <footer class="modal-footer">
                        <button type="submit" class="form-btn submit-btn">حفظ</button>
                        <button type="button" class="form-btn cancel-btn" onclick="closeModal('addFeeModal')">إلغاء</button>
                    </footer>
                </form>
                <?php elseif ($searchBy): ?>
                    <p class="blink" style="color: red; margin-top: 20px;">يرجى تحديد ملف صالح من نتائج البحث.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="js/popups.js"></script>
    <script src="js/checkAll.js"></script>
    <script src="js/dropdown.js"></script>
</body>
</html>
