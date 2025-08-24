<?php
// FILE: BookedJud.php

/**
 * This page handles the form for booking a judgment for a specific case file.
 * It performs security checks and populates the form with relevant data.
 *
 * GET Params:
 * - fid: The file ID (required, integer).
 * - sid: Session ID (used in included files).
 * - deg: The degree of the case (optional, string).
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
// It's better to have a single bootstrap file, but we'll keep the existing includes for now.
include_once 'connection.php';
include_once 'login_check.php';
include_once 'safe_output.php';
include_once 'permissions_check.php';
include_once 'golden_pass.php'; // This might grant admin-like privileges.

// 2. INPUT VALIDATION & INITIALIZATION
// =============================================================================
$fileId = isset($_GET['fid']) ? (int)$_GET['fid'] : 0;
$degree = isset($_GET['deg']) ? htmlspecialchars($_GET['deg']) : '';
$sid = isset($_GET['sid']) ? htmlspecialchars($_GET['sid']) : '';

if ($fileId <= 0) {
    http_response_code(400);
    die('Invalid File ID.');
}

// 3. PERMISSIONS & DATA FETCHING
// =============================================================================

// Check general session permissions first. The form is only for users who can add or edit sessions.
if ($row_permcheck['session_aperm'] != 1 && $row_permcheck['session_eperm'] != 1) {
    http_response_code(403);
    die('You do not have permission to perform this action.');
}

// Fetch main file details
$stmt = $conn->prepare("SELECT file_id, secret_folder, secret_emps, file_type FROM file WHERE file_id = ?");
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
        die('Access denied to this secret file.');
    }
}

// Fetch file degrees for the dropdown
$fileDegrees = [];
$stmt_degrees = $conn->prepare("SELECT file_year, case_num, degree FROM file_degrees WHERE fid = ?");
$stmt_degrees->bind_param("i", $fileId);
$stmt_degrees->execute();
$result_degrees = $stmt_degrees->get_result();
while ($row = $result_degrees->fetch_assoc()) {
    $fileDegrees[] = $row;
}
$stmt_degrees->close();

?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حجز القضية للحكم</title>
    
    <!-- Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/styles.css" rel="stylesheet">
    <link rel="shortcut icon" href="files/images/instance/favicon.ico" type="image/x-icon">

    <style>
        /* Page-specific styles */
        .blue-parag { color: #007bff; }
        .required-star { color: #FF0000; }
        .input-parag { margin-bottom: 5px; }
        .radio-group label { margin-left: 15px; }
    </style>
</head>
<body>

<div class="advinputs-container" style="height: fit-content; overflow-y: auto;">
    <form name="addForm" id="addForm" action="booked.php" method="post" enctype="multipart/form-data">
        <input name="fid" type="hidden" value="<?php echo safe_output($fileId); ?>">
        
        <h2 class="advinputs-h2">حجزت القضية للحكم</h2>
        
        <div class="advinputs">
            <div class="input-container">
                <label for="booked_todate" class="input-parag blue-parag">حتى تاريخ <span class="required-star">*</span></label>
                <input id="booked_todate" class="form-input" type="date" name="booked_todate" required>
            </div>
            
            <div class="input-container">
                <label for="degree_id_sess" class="input-parag blue-parag">درجة التقاضي <span class="required-star">*</span></label>
                <select id="degree_id_sess" name="degree_id_sess" class="table-header-selector" style="width: 80%; height: fit-content; margin: 10px 0;" required>
                    <option value=""></option>
                    <?php foreach ($fileDegrees as $deg) : ?>
                        <?php $value = safe_output($deg['file_year'] . '/' . $deg['case_num'] . '-' . $deg['degree']); ?>
                        <option value="<?php echo $value; ?>">
                            <?php echo safe_output($deg['file_year'] . ' / ' . $deg['case_num'] . ' - ' . $deg['degree']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="advinputs">
            <div class="input-container">
                <label for="booked_detail" class="input-parag blue-parag">التفاصيل</label>
                <textarea id="booked_detail" class="form-input" name="booked_detail" rows="2"></textarea>
            </div>
            
            <?php // Conditional Fields based on degree and file type ?>
            <div class="input-container">
                <?php if ($fileDetails['file_type'] === 'مدني -عمالى' && in_array($degree, ['ابتدائي', 'استئناف'])): ?>
                    <label>
                        <input type="checkbox" name="amount" value="1"> اكثر من 500,000 درهم
                    </label>
                <?php elseif (in_array($degree, ['امر على عريضة', 'حجز تحفظي'])): ?>
                    <p class="blue-parag">قرار القاضي</p>
                    <div class="radio-group">
                        <label><input type="radio" name="decission" value="0"> رفض</label>
                        <label><input type="radio" name="decission" value="1"> قبول</label>
                    </div>
                <?php elseif ($degree === 'امر اداء'): ?>
                    <label>
                        <input type="checkbox" name="amount" value="2"> اكثر من 50,000 درهم
                    </label>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="advinputs3">
            <button type="submit" class="green-button">حفظ البيانات</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addForm = document.getElementById('addForm');
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent traditional form submission

            const formData = new FormData(this);

            fetch('booked.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    return response.text(); // Or response.json() if your backend returns JSON
                }
                throw new Error('Network response was not ok.');
            })
            .then(data => {
                Swal.fire({
                    title: 'نجاح!',
                    text: 'تم حفظ البيانات بنجاح.',
                    icon: 'success',
                    confirmButtonText: 'موافق'
                }).then(() => {
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                    }
                    window.close();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'خطأ!',
                    text: 'حدث خطأ أثناء حفظ البيانات.',
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
