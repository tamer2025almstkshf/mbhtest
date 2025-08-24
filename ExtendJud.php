<?php
// FILE: ExtendJud.php

/**
 * This page provides a form to extend the judgment date for a specific court session.
 * It performs security checks to ensure the user has the correct permissions
 * and access to the associated file.
 *
 * GET Params:
 * - sid: The session ID (required, integer).
 * - fid: The file ID (required, integer).
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
include_once 'connection.php';
include_once 'login_check.php';
include_once 'safe_output.php';
include_once 'permissions_check.php';
include_once 'golden_pass.php';

// 2. PERMISSIONS & INPUT VALIDATION
// =============================================================================

// First, check if the user has general permission to add or edit sessions.
if ($row_permcheck['session_aperm'] != 1 && $row_permcheck['session_eperm'] != 1) {
    http_response_code(403);
    die('Access Denied: You do not have permission to perform this action.');
}

// Validate and sanitize input GET parameters.
$sessionId = isset($_GET['sid']) ? (int)$_GET['sid'] : 0;
$fileId = isset($_GET['fid']) ? (int)$_GET['fid'] : 0;

if ($sessionId <= 0 || $fileId <= 0) {
    http_response_code(400);
    die('Invalid session or file ID.');
}

// 3. DATA FETCHING & SECURITY CHECKS
// =============================================================================

// Fetch file details to check for secret folder access.
$stmt = $conn->prepare("SELECT secret_folder, secret_emps FROM file WHERE file_id = ?");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$result = $stmt->get_result();
$fileDetails = $result->fetch_assoc();
$stmt->close();

if (!$fileDetails) {
    http_response_code(404);
    die('File not found.');
}

// Security Check: If the file is in a secret folder, verify user access.
if ($admin != 1 && $fileDetails['secret_folder'] == 1) {
    $allowedUserIds = array_filter(array_map('trim', explode(',', $fileDetails['secret_emps'])));
    if (!in_array($_SESSION['id'], $allowedUserIds, true)) {
        http_response_code(403);
        die('Access Denied: You do not have permission to access this secret file.');
    }
}

?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مد أجل الحكم</title>
    
    <!-- Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/styles.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>

<div class="advinputs-container">
    <form id="extendForm" action="extend.php" method="post">
        <input type="hidden" name="session_id" value="<?php echo safe_output($sessionId); ?>">
        <input type="hidden" name="session_fid" value="<?php echo safe_output($fileId); ?>">
        
        <h2 class="advinputs-h2">مد أجل الحكم</h2>
        
        <div class="advinputs">
            <div class="input-container">
                <label for="booked_todate" class="input-parag blue-parag">
                    حتى تاريخ <span class="required-star">*</span>
                </label>
                <input id="booked_todate" class="form-input" type="date" name="booked_todate" required>
            </div>
            
            <div class="input-container">
                <label for="booked_detail" class="input-parag blue-parag">التفاصيل</label>
                <textarea id="booked_detail" class="form-input" name="booked_detail" rows="3"></textarea>
            </div>
        </div>

        <div class="advinputs3">
            <button type="submit" class="green-button">حفظ البيانات</button>
            <button type="button" class="form-btn cancel-btn" onclick="window.close();">إلغاء</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const extendForm = document.getElementById('extendForm');
    
    if (extendForm) {
        extendForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Stop the default form submission

            const formData = new FormData(this);

            fetch('extend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    return response.text(); // Or .json() if the server returns JSON
                }
                // If response is not ok, throw an error to be caught by .catch()
                throw new Error('Network response was not ok.');
            })
            .then(data => {
                // Success feedback to the user
                Swal.fire({
                    title: 'نجاح!',
                    text: 'تم حفظ البيانات بنجاح.',
                    icon: 'success',
                    confirmButtonText: 'موافق'
                }).then(() => {
                    // Reload the opener window and close the popup
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                    }
                    window.close();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                // Error feedback to the user
                Swal.fire({
                    title: 'خطأ!',
                    text: 'حدث خطأ أثناء محاولة حفظ البيانات.',
                    icon: 'error',
                    confirmButtonText: 'موافق'
                });
            });
        });
    }
});
</script>

</body>
</html>
