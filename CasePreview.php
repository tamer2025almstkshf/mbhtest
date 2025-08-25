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
include_once 'src/I18n.php';

$i18n = new I18n('translations/CasePreview.yaml');

// 2. PERMISSIONS CHECK
// =============================================================================
if ($row_permcheck['cfiles_rperm'] != 1) {
    // It's better to show a proper "Access Denied" page than a blank one.
    http_response_code(403);
    die($i18n->get('no_permission_to_view'));
}

// 3. INPUT VALIDATION & INITIALIZATION
// =============================================================================
$fileId = filter_input(INPUT_GET, 'fid', FILTER_VALIDATE_INT);

if (!$fileId || $fileId <= 0) {
    http_response_code(400);
    die($i18n->get('invalid_file_id'));
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
    die($i18n->get('file_not_found'));
}

// Security Check: Secret Folder Access
if ($admin != 1 && $file['secret_folder'] == 1) {
    $allowedUserIds = array_filter(array_map('trim', explode(',', $file['secret_emps'])));
    if (!in_array($_SESSION['id'], $allowedUserIds, true)) {
        http_response_code(403);
        die($i18n->get('access_restricted'));
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
<html lang="<?php echo $i18n->getLocale(); ?>" dir="<?php echo $i18n->getDirection(); ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $i18n->get('file_details'); ?> - <?php echo safe_output($file['file_id']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/sites.css" rel="stylesheet">
    <link href="css/preview_styles.css" rel="stylesheet"> <!-- A new stylesheet for preview page -->
    <link rel="SHORTCUT ICON" href="img/favicon.ico">
</head>
<body>

    <div class="print-btn-container">
        <button id="printBtn" class="print-btn" onclick="window.print();">
            <i class='bx bx-printer'></i> <?php echo $i18n->get('print'); ?>
        </button>
    </div>

    <div class="container" id="PrintMainDiv">
        <main class="main-content">
            <header id="case-header" class="case-header">
                <h1>
                    <?php echo $i18n->get('file_details'); ?>
                    <span class="case-number">
                        <?php echo safe_output(getFilePrefix($file['frelated_place']) . " " . $file['file_id']); ?>
                    </span>
                </h1>
            </header>

            <!-- Case Info Section -->
            <section class="info-section">
                <h2><i class='bx bxs-folder-open'></i> <?php echo $i18n->get('file_info'); ?></h2>
                <div class="info-grid">
                    <div class="info-box"><h4><?php echo $i18n->get('file_type'); ?></h4><p><?php echo safe_output($file['file_type']); ?></p></div>
                    <div class="info-box"><h4><?php echo $i18n->get('file_classification'); ?></h4><p><?php echo safe_output($file['file_class']); ?></p></div>
                    <div class="info-box"><h4><?php echo $i18n->get('police_station'); ?></h4><p><?php echo safe_output($file['fpolice_station']); ?></p></div>
                    <div class="info-box"><h4><?php echo $i18n->get('court'); ?></h4><p><?php echo safe_output($file['file_court']); ?></p></div>
                    <div class="info-box wide"><h4><?php echo $i18n->get('subject'); ?></h4><p><?php echo safe_output($file['file_subject']); ?></p></div>
                    <div class="info-box wide"><h4><?php echo $i18n->get('notes'); ?></h4><p><?php echo nl2br(safe_output($file['file_notes'])); ?></p></div>
                    <div class="info-box"><h4><?php echo $i18n->get('legal_advisor'); ?></h4><p><?php echo safe_output($legalAdvisorName); ?></p></div>
                    <div class="info-box"><h4><?php echo $i18n->get('legal_researcher'); ?></h4><p><?php echo safe_output($legalResearcherName); ?></p></div>
                </div>
            </section>
            
            <!-- Accordion Sections -->
            <div class="accordion">
                <!-- Clients Section -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span><i class='bx bxs-user'></i> <?php echo $i18n->get('clients'); ?></span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="accordion-content">
                        <?php if (empty($clients)): ?>
                            <p><?php echo $i18n->get('no_clients_registered'); ?></p>
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
                        <span><i class='bx bxs-layer'></i> <?php echo $i18n->get('litigation_degrees'); ?> (<?php echo count($degrees); ?>)</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="accordion-content">
                        <?php if (empty($degrees)): ?>
                            <p><?php echo $i18n->get('no_litigation_degrees_registered'); ?></p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th><?php echo $i18n->get('degree'); ?></th>
                                        <th><?php echo $i18n->get('case_number'); ?></th>
                                        <th><?php echo $i18n->get('client_characteristic'); ?></th>
                                        <th><?php echo $i18n->get('opponent_characteristic'); ?></th>
                                        <th><?php echo $i18n->get('entry_date'); ?></th>
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
                        <span><i class='bx bxs-calendar-event'></i> <?php echo $i18n->get('sessions'); ?> (<?php echo count($sessions); ?>)</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="accordion-content">
                        <?php if (empty($sessions)): ?>
                            <p><?php echo $i18n->get('no_sessions_registered'); ?></p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th><?php echo $i18n->get('session_date'); ?></th>
                                        <th><?php echo $i18n->get('litigation_degrees'); ?></th>
                                        <th><?php echo $i18n->get('details'); ?></th>
                                        <th><?php echo $i18n->get('judgment_text'); ?></th>
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
                        <span><i class='bx bx-task'></i> <?php echo $i18n->get('administrative_tasks'); ?> (<?php echo count($tasks); ?>)</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="accordion-content">
                        <?php if (empty($tasks)): ?>
                            <p><?php echo $i18n->get('no_administrative_tasks_registered'); ?></p>
                        <?php else: ?>
                        <div class="table-responsive">
                             <table class="data-table">
                                <thead>
                                    <tr>
                                        <th><?php echo $i18n->get('due_date'); ?></th>
                                        <th><?php echo $i18n->get('task'); ?></th>
                                        <th><?php echo $i18n->get('legal_researcher'); ?></th>
                                        <th><?php echo $i18n->get('details'); ?></th>
                                        <th><?php echo $i18n->get('importance'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td><?php echo safe_output($task['duedate']); ?></td>
                                        <td><?php echo safe_output($task['job_name']); ?></td>
                                        <td><?php echo safe_output($task['employee_name']); ?></td>
                                        <td><?php echo safe_output($task['details']); ?></td>
                                        <td><?php echo $task['priority'] == 1 ? '<span class="priority-urgent">' . $i18n->get('urgent') . '</span>' : $i18n->get('normal'); ?></td>
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
                        <span><i class='bx bxs-paperclip'></i> <?php echo $i18n->get('attachments'); ?> (<?php echo count($attachments) + count($documents); ?>)</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="accordion-content">
                        <?php if (empty($attachments) && empty($documents)): ?>
                            <p><?php echo $i18n->get('no_attachments'); ?></p>
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
                                    <?php echo $i18n->get('uploaded_by'); ?>: <?php echo safe_output($att['uploader_name']); ?>
                                    <?php echo $i18n->get('date'); ?>: <?php echo safe_output($att['timestamp']); ?>
                                </span>
                            </li>
                            <?php endforeach; ?>
                            <?php foreach ($documents as $doc): ?>
                            <li>
                                <i class='bx bxs-file-doc'></i>
                                <a href="AddNotes.php?fno=<?php echo safe_output($doc['dfile_no']); ?>&id=<?php echo safe_output($doc['did']); ?>&edit=1" target="_blank">
                                    <?php echo safe_output($doc['document_subject']); ?> (<?php echo $i18n->get('memo'); ?>)
                                </a>
                                <span class="meta">
                                    <?php echo $i18n->get('date'); ?>: <?php echo safe_output($doc['document_date']); ?>
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
