<?php
// FILE: Fees.php

/**
 * This page allows users to manage the fee agreement stages (levels) for a specific case file.
 *
 * GET Params:
 * - id: The File ID (required, integer).
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'golden_pass.php';

// 2. PERMISSIONS & INPUT VALIDATION
// =============================================================================

// Check if the user has permission to edit levels.
if ($row_permcheck['levels_eperm'] != 1) {
    http_response_code(403);
    die('Access Denied: You do not have permission to perform this action.');
}

// Validate and sanitize the file ID from the GET request.
$fileId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($fileId <= 0) {
    http_response_code(400);
    die('Invalid File ID provided.');
}

// 3. DATA FETCHING & PROCESSING
// =============================================================================

// Fetch file details for security checks and to get current levels.
$stmt = $conn->prepare("SELECT secret_folder, secret_emps, file_levels FROM file WHERE file_id = ?");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$result = $stmt->get_result();
$fileDetails = $result->fetch_assoc();
$stmt->close();

if (!$fileDetails) {
    http_response_code(404);
    die('File not found.');
}

// Security Check: Verify access to secret folders.
if ($admin != 1 && $fileDetails['secret_folder'] == 1) {
    $allowedUserIds = array_filter(array_map('trim', explode(',', $fileDetails['secret_emps'])));
    if (!in_array($_SESSION['id'], $allowedUserIds, true)) {
        http_response_code(403);
        die('Access to this secret file is restricted.');
    }
}

// Get the currently selected levels for this file.
$selectedLevels = array_map('trim', explode(',', $fileDetails['file_levels']));

// Fetch all available levels from the database.
$allLevels = [];
$stmt = $conn->prepare("SELECT id, level_name FROM levels ORDER BY level_name ASC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $allLevels[] = $row;
}
$stmt->close();

?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مراحل الاتفاقية</title>
    
    <!-- Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/styles.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>

<div class="advinputs-container">
    <form id="feesForm" name="feesForm" action="addfilefees.php" method="post">
        <input type="hidden" name="fid" value="<?php echo safe_output($fileId); ?>">

        <div class="advinputs">
            <div class="input-container">
                <p class="input-parag">
                    <span class="blue-parag">مراحل الاتفاقية</span>
                    <?php if ($row_permcheck['selectors_rperm'] == 1): ?>
                        <a href="#" onclick="window.open('selector/Levels.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400'); return false;" title="إضافة مرحلة جديدة">
                            <i class='bx bxs-add-to-queue'></i>
                        </a>
                    <?php endif; ?>
                </p>
                
                <div class="checkbox-group">
                    <?php if (empty($allLevels)): ?>
                        <p>لا توجد مراحل معرفة في النظام.</p>
                    <?php else: ?>
                        <?php foreach ($allLevels as $level): ?>
                            <div class="checkbox-item">
                                <input type="checkbox" name="levels[]" class="user-checkbox" 
                                       id="level_<?php echo safe_output($level['id']); ?>"
                                       value="<?php echo safe_output($level['id']); ?>"
                                       <?php if (in_array($level['level_name'], $selectedLevels)) echo 'checked'; ?>>
                                <label for="level_<?php echo safe_output($level['id']); ?>">
                                    <?php echo safe_output($level['level_name']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="advinputs3">
            <button type="submit" class="green-button">حفظ البيانات</button>
            <button type="button" class="form-btn cancel-btn" onclick="window.close();">إلغاء</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('feesForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('addfilefees.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                Swal.fire({
                    title: 'نجاح!',
                    text: 'تم تحديث مراحل الاتفاقية بنجاح.',
                    icon: 'success',
                    confirmButtonText: 'موافق'
                }).then(() => {
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                    }
                    window.close();
                });
            } else {
                throw new Error('An error occurred while saving the data.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'خطأ!',
                text: 'حدث خطأ أثناء محاولة حفظ البيانات.',
                icon: 'error',
                confirmButtonText: 'موافق'
            });
        });
    });
});
</script>

</body>
</html>
