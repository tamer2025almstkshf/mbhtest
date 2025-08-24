<?php
// FILE: CasePreview.php

/**
 * Displays a comprehensive preview of a case file.
 * Includes case details, clients, litigation degrees, sessions, tasks, and attachments.
 *
 * GET Params:
 * - fid: The file ID (required, integer).
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
include_once 'connection.php';
include_once 'login_check.php';
include_once 'safe_output.php';
include_once 'permissions_check.php';
include_once 'golden_pass.php';

// 2. PERMISSIONS CHECK
// =============================================================================
if ($row_permcheck['cfiles_rperm'] != 1) {
    // It's better to show a proper "Access Denied" page than a blank one.
    http_response_code(403);
    die('You do not have permission to view this page.');
}

// 3. INPUT VALIDATION & INITIALIZATION
// =============================================================================
$fileId = filter_input(INPUT_GET, 'fid', FILTER_VALIDATE_INT);

if (!$fileId || $fileId <= 0) {
    http_response_code(400);
    die('Invalid or missing File ID.');
}

// 4. DATA FETCHING
// =============================================================================

// Fetch main file details
$stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$result = $stmt->get_result();
$file = $result->fetch_assoc();
$stmt->close();

if (!$file) {
    http_response_code(404);
    die('File not found.');
}

// Security Check: Secret Folder Access
if ($admin != 1 && $file['secret_folder'] == 1) {
    $allowedUserIds = array_filter(array_map('trim', explode(',', $file['secret_emps'])));
    if (!in_array($_SESSION['id'], $allowedUserIds, true)) {
        http_response_code(403);
        die('Access to this file is restricted.');
    }
}

// Fetch Clients
$clients = [];
$clientIds = array_filter([
    $file['file_client'], $file['file_client2'], $file['file_client3'], $file['file_client4'], $file['file_client5']
]);
$clientCharacteristics = [
    $file['fclient_characteristic'], $file['fclient_characteristic2'], $file['fclient_characteristic3'],
    $file['fclient_characteristic4'], $file['fclient_characteristic5']
];

if (!empty($clientIds)) {
    $placeholders = implode(',', array_fill(0, count($clientIds), '?'));
    $stmt_clients = $conn->prepare("SELECT id, arname FROM client WHERE id IN ($placeholders)");
    $stmt_clients->bind_param(str_repeat('i', count($clientIds)), ...$clientIds);
    $stmt_clients->execute();
    $clientResults = $stmt_clients->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_clients->close();

    // Map characteristics to clients
    foreach ($clientResults as $i => $client) {
        $clients[] = [
            'name' => $client['arname'],
            'characteristic' => $clientCharacteristics[$i]
        ];
    }
}


// Fetch Advisors and Researchers
function getUserName($conn, $userId) {
    if (empty($userId)) return '';
    $stmt = $conn->prepare("SELECT name FROM user WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user['name'] ?? 'N/A';
}
$legalAdvisorName = getUserName($conn, $file['flegal_advisor']);
$legalResearcherName = getUserName($conn, $file['flegal_researcher']);

// Fetch Litigation Degrees
$degrees = [];
$stmt_degrees = $conn->prepare("SELECT * FROM file_degrees WHERE fid = ? ORDER BY created_at DESC");
$stmt_degrees->bind_param("i", $fileId);
$stmt_degrees->execute();
$result_degrees = $stmt_degrees->get_result();
while ($row = $result_degrees->fetch_assoc()) {
    $degrees[] = $row;
}
$stmt_degrees->close();

// Fetch Sessions
$sessions = [];
$stmt_sessions = $conn->prepare("SELECT * FROM session WHERE session_fid = ? ORDER BY session_date DESC");
$stmt_sessions->bind_param("i", $fileId);
$stmt_sessions->execute();
$result_sessions = $stmt_sessions->get_result();
while ($row = $result_sessions->fetch_assoc()) {
    $sessions[] = $row;
}
$stmt_sessions->close();

// Fetch Administrative Tasks
$tasks = [];
$stmt_tasks = $conn->prepare("
    SELECT t.*, j.job_name, u_emp.name as employee_name, u_resp.name as responsible_name, 
           fd.case_num, fd.file_year, fd.degree as degree_name
    FROM tasks t
    LEFT JOIN job_name j ON t.task_type = j.id
    LEFT JOIN user u_emp ON t.employee_id = u_emp.id
    LEFT JOIN user u_resp ON t.responsible = u_resp.id
    LEFT JOIN file_degrees fd ON t.degree = fd.id
    WHERE t.file_no = ?
    ORDER BY t.duedate DESC
");
$stmt_tasks->bind_param("i", $fileId);
$stmt_tasks->execute();
$result_tasks = $stmt_tasks->get_result();
while ($row = $result_tasks->fetch_assoc()) {
    $tasks[] = $row;
}
$stmt_tasks->close();

// Fetch Attachments and Documents
$attachments = [];
$stmt_atts = $conn->prepare("SELECT f.*, u.name as uploader_name FROM files_attachments f LEFT JOIN user u ON f.done_by = u.id WHERE f.fid = ?");
$stmt_atts->bind_param("i", $fileId);
$stmt_atts->execute();
$result_atts = $stmt_atts->get_result();
while ($row = $result_atts->fetch_assoc()) {
    $attachments[] = $row;
}
$stmt_atts->close();

$documents = [];
$stmt_docs = $conn->prepare("SELECT * FROM case_document WHERE dfile_no = ?");
$stmt_docs->bind_param("i", $fileId);
$stmt_docs->execute();
$result_docs = $stmt_docs->get_result();
while ($row = $result_docs->fetch_assoc()) {
    $documents[] = $row;
}
$stmt_docs->close();

// Helper for file prefix
function getFilePrefix($place) {
    switch ($place) {
        case 'الشارقة': return 'SHJ';
        case 'دبي': return 'DXB';
        case 'عجمان': return 'AJM';
        default: return '';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>تفاصيل الملف - <?php echo safe_output($file['file_id']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/sites.css" rel="stylesheet">
    <link href="css/preview_styles.css" rel="stylesheet"> <!-- A new stylesheet for preview page -->
    <link rel="SHORTCUT ICON" href="img/favicon.ico">
</head>
<body>

    <div class="print-btn-container">
        <button id="printBtn" class="print-btn" onclick="window.print();">
            <i class='bx bx-printer'></i> طباعة
        </button>
    </div>

    <div class="container" id="PrintMainDiv">
        <main class="main-content">
            <header id="case-header" class="case-header">
                <h1>
                    تفاصيل الملف رقم
                    <span class="case-number">
                        <?php echo safe_output(getFilePrefix($file['frelated_place']) . " " . $file['file_id']); ?>
                    </span>
                </h1>
            </header>

            <!-- Case Info Section -->
            <section class="info-section">
                <h2><i class='bx bxs-folder-open'></i> معلومات الملف</h2>
                <div class="info-grid">
                    <div class="info-box"><h4>نوع الملف</h4><p><?php echo safe_output($file['file_type']); ?></p></div>
                    <div class="info-box"><h4>تصنيف الملف</h4><p><?php echo safe_output($file['file_class']); ?></p></div>
                    <div class="info-box"><h4>مركز الشرطة</h4><p><?php echo safe_output($file['fpolice_station']); ?></p></div>
                    <div class="info-box"><h4>المحكمة</h4><p><?php echo safe_output($file['file_court']); ?></p></div>
                    <div class="info-box wide"><h4>الموضوع</h4><p><?php echo safe_output($file['file_subject']); ?></p></div>
                    <div class="info-box wide"><h4>ملاحظات</h4><p><?php echo nl2br(safe_output($file['file_notes'])); ?></p></div>
                    <div class="info-box"><h4>المستشار القانوني</h4><p><?php echo safe_output($legalAdvisorName); ?></p></div>
                    <div class="info-box"><h4>الباحث القانوني</h4><p><?php echo safe_output($legalResearcherName); ?></p></div>
                </div>
            </section>
            
            <!-- Accordion Sections -->
            <div class="accordion">
                <!-- Clients Section -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span><i class='bx bxs-user'></i> الموكلين</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="accordion-content">
                        <?php if (empty($clients)): ?>
                            <p>لا يوجد موكلين مسجلين.</p>
                        <?php else: ?>
                            <ul>
                                <?php foreach ($clients as $client): ?>
                                    <li><?php echo safe_output($client['name']); ?> / <?php echo safe_output($client['characteristic']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Degrees Section -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span><i class='bx bxs-layer'></i> درجات التقاضي (<?php echo count($degrees); ?>)</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="accordion-content">
                        <?php if (empty($degrees)): ?>
                            <p>لا توجد درجات تقاضي مسجلة.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>الدرجة</th>
                                        <th>رقم القضية</th>
                                        <th>صفة الموكل</th>
                                        <th>صفة الخصم</th>
                                        <th>تاريخ الإدخال</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($degrees as $degree): ?>
                                    <tr>
                                        <td><?php echo safe_output($degree['degree']); ?></td>
                                        <td><?php echo safe_output($degree['case_num'] . '/' . $degree['file_year']); ?></td>
                                        <td><?php echo safe_output($degree['client_characteristic']); ?></td>
                                        <td><?php echo safe_output($degree['opponent_characteristic']); ?></td>
                                        <td><?php echo safe_output($degree['timestamp']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Sessions Section -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span><i class='bx bxs-calendar-event'></i> الجلسات (<?php echo count($sessions); ?>)</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="accordion-content">
                        <?php if (empty($sessions)): ?>
                            <p>لا توجد جلسات مسجلة.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>تاريخ الجلسة</th>
                                        <th>درجة التقاضي</th>
                                        <th>التفاصيل</th>
                                        <th>منطوق الحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($sessions as $session): ?>
                                    <tr>
                                        <td><?php echo safe_output($session['session_date']); ?></td>
                                        <td><?php echo safe_output($session['case_num'] . '/' . $session['year'] . '-' . $session['session_degree']); ?></td>
                                        <td><?php echo safe_output($session['session_details']); ?></td>
                                        <td><?php echo safe_output($session['session_trial']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tasks Section -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span><i class='bx bx-task'></i> المهام الإدارية (<?php echo count($tasks); ?>)</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="accordion-content">
                        <?php if (empty($tasks)): ?>
                            <p>لا توجد مهام إدارية مسجلة.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                             <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ت.التنفيذ</th>
                                        <th>المهمة</th>
                                        <th>الباحث القانوني</th>
                                        <th>التفاصيل</th>
                                        <th>الأهمية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td><?php echo safe_output($task['duedate']); ?></td>
                                        <td><?php echo safe_output($task['job_name']); ?></td>
                                        <td><?php echo safe_output($task['employee_name']); ?></td>
                                        <td><?php echo safe_output($task['details']); ?></td>
                                        <td><?php echo $task['priority'] == 1 ? '<span class="priority-urgent">عاجل</span>' : 'عادي'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Attachments Section -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span><i class='bx bxs-paperclip'></i> المرفقات (<?php echo count($attachments) + count($documents); ?>)</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="accordion-content">
                        <?php if (empty($attachments) && empty($documents)): ?>
                            <p>لا توجد مرفقات.</p>
                        <?php else: ?>
                        <ul class="attachment-list">
                            <?php foreach ($attachments as $att): ?>
                            <li>
                                <i class='bx bxs-file'></i>
                                <a href="<?php echo safe_output($att['attachment']); ?>" target="_blank">
                                    <?php echo safe_output(basename($att['attachment'])); ?>
                                </a>
                                <span class="meta">
                                    (<?php echo safe_output($att['attachment_size']); ?>) - 
                                    رفع بواسطة: <?php echo safe_output($att['uploader_name']); ?>
                                    بتاريخ: <?php echo safe_output($att['timestamp']); ?>
                                </span>
                            </li>
                            <?php endforeach; ?>
                            <?php foreach ($documents as $doc): ?>
                            <li>
                                <i class='bx bxs-file-doc'></i>
                                <a href="AddNotes.php?fno=<?php echo safe_output($doc['dfile_no']); ?>&id=<?php echo safe_output($doc['did']); ?>&edit=1" target="_blank">
                                    <?php echo safe_output($doc['document_subject']); ?> (مذكرة)
                                </a>
                                <span class="meta">
                                    بتاريخ: <?php echo safe_output($doc['document_date']); ?>
                                </span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>

            </div> <!-- /accordion -->
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const accordionHeaders = document.querySelectorAll('.accordion-header');
            accordionHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const accordionItem = header.parentElement;
                    const accordionContent = header.nextElementSibling;
                    
                    accordionItem.classList.toggle('active');

                    if (accordionItem.classList.contains('active')) {
                        accordionContent.style.maxHeight = accordionContent.scrollHeight + "px";
                    } else {
                        accordionContent.style.maxHeight = 0;
                    }
                });
            });
        });
    </script>
</body>
</html>
