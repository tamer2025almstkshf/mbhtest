<?php
// FILE: Judgement.php

/**
 * This page provides a form to record the final judgment for a specific court session.
 * It performs security checks for user permissions and file access.
 *
 * GET Params:
 * - sid: The session ID (required, integer).
 * - fid: The file ID (required, integer).
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

// Check for general session permissions first.
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

// Fetch file details for secret folder access check.
$stmt = $conn->prepare("SELECT secret_folder, secret_emps FROM file WHERE file_id = ?");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$fileDetails = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$fileDetails) {
    http_response_code(404);
    die('File not found.');
}

// Security Check for secret folders.
if ($admin != 1 && $fileDetails['secret_folder'] == 1) {
    $allowedUserIds = array_filter(array_map('trim', explode(',', $fileDetails['secret_emps'])));
    if (!in_array($_SESSION['id'], $allowedUserIds, true)) {
        http_response_code(403);
        die('Access Denied: You do not have permission to access this secret file.');
    }
}

// Fetch existing session data to pre-fill the form.
$stmt = $conn->prepare("SELECT session_trial, resume_appeal FROM session WHERE session_id = ?");
$stmt->bind_param("i", $sessionId);
$stmt->execute();
$sessionData = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$sessionData) {
    http_response_code(404);
    die('Session not found.');
}

// Determine the type of follow-up action (appeal, grievance, etc.).
$followUpTypeMap = [
    '1' => 'الاستئناف',
    '2' => 'الطعن',
    '3' => 'التظلم',
    '4' => 'المعارضة'
];
$followUpType = $followUpTypeMap[$sessionData['resume_appeal']] ?? '';

?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>تسجيل منطوق الحكم</title>
    
    <!-- Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/styles.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>

<div class="advinputs-container">
    <form id="judgmentForm" action="judge.php" method="post">
        <input type="hidden" name="session_fid" value="<?php echo safe_output($fileId); ?>">
        <input type="hidden" name="session_id" value="<?php echo safe_output($sessionId); ?>">
        
        <h2 class="advinputs-h2">تسجيل منطوق الحكم</h2>
        
        <div class="advinputs">
            <div class="input-container">
                <label for="booked_final" class="input-parag blue-parag">منطوق الحكم</label>
                <textarea id="booked_final" class="form-input" name="booked_final" rows="4"><?php echo safe_output($sessionData['session_trial']); ?></textarea>
            </div>
            
            <?php if (!empty($followUpType)): ?>
            <div class="input-container">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="continue" value="1" id="continue_follow_up" checked>
                    <label class="form-check-label blue-parag" for="continue_follow_up">
                        متابعة <?php echo safe_output($followUpType); ?>
                    </label>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="advinputs3">
            <button type="submit" class="green-button">حفظ منطوق الحكم</button>
            <button type="button" class="form-btn cancel-btn" onclick="window.close();">إلغاء</button>
        </div>
    </form>
</div>

<script>
document.getElementById('judgmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('judge.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            Swal.fire({
                title: 'نجاح!',
                text: 'تم حفظ منطوق الحكم بنجاح.',
                icon: 'success',
                confirmButtonText: 'موافق'
            }).then(() => {
                if (window.opener && !window.opener.closed) {
                    window.opener.location.reload();
                }
                window.close();
            });
        } else {
            throw new Error('Network response was not ok.');
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
</script>

</body>
</html>
