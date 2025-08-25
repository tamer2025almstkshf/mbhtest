<?php
// FILE: FileEdit.php

// 1. INCLUDES & BOOTSTRAPPING
require_once __DIR__ . '/bootstrap.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'golden_pass.php';

// 2. PERMISSIONS & INPUT VALIDATION
if ($row_permcheck['cfiles_eperm'] != 1) {
    http_response_code(403);
    die(__('no_permission_to_edit'));
}

$fileId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($fileId <= 0) {
    http_response_code(400);
    die(__('invalid_file_id'));
}

// 3. DATA FETCHING (Consolidated)
// Fetch core file details
$stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$fileDetails = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$fileDetails) {
    http_response_code(404);
    die(__('file_not_found'));
}

// Security check for secret folder
if ($admin != 1 && $fileDetails['secret_folder'] == 1) {
    $allowedUserIds = array_filter(array_map('trim', explode(',', $fileDetails['secret_emps'])));
    if (!in_array($_SESSION['id'], $allowedUserIds, true)) {
        http_response_code(403);
        die(__('restricted_access_secret_file'));
    }
}

// Fetch all data needed for dropdowns and lists
$data = [
    'file_classes' => $conn->query("SELECT * FROM fclass")->fetch_all(MYSQLI_ASSOC),
    'clients' => $conn->query("SELECT id, arname FROM client WHERE terror != '1' AND arname != '' AND client_kind='موكل' ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC),
    'opponents' => $conn->query("SELECT id, arname FROM client WHERE terror != '1' AND arname != '' AND client_kind='خصم' ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC),
    'client_statuses' => $conn->query("SELECT * FROM client_status")->fetch_all(MYSQLI_ASSOC),
    'case_types' => $conn->query("SELECT * FROM case_type")->fetch_all(MYSQLI_ASSOC),
    'police_stations' => $conn->query("SELECT * FROM police_station")->fetch_all(MYSQLI_ASSOC),
    'prosecutions' => $conn->query("SELECT * FROM prosecution")->fetch_all(MYSQLI_ASSOC),
    'courts' => $conn->query("SELECT * FROM court")->fetch_all(MYSQLI_ASSOC),
    'secretaries' => $conn->query("SELECT id, name FROM user WHERE job_title='14'")->fetch_all(MYSQLI_ASSOC),
    'researchers' => $conn->query("SELECT id, name FROM user WHERE job_title='11'")->fetch_all(MYSQLI_ASSOC),
    'advisors' => $conn->query("SELECT id, name FROM user WHERE job_title='10'")->fetch_all(MYSQLI_ASSOC),
    'lawyers' => $conn->query("SELECT id, name FROM user WHERE job_title='13'")->fetch_all(MYSQLI_ASSOC),
    'degrees' => $conn->query("SELECT * FROM degree")->fetch_all(MYSQLI_ASSOC),
    'job_names' => $conn->query("SELECT * FROM job_name")->fetch_all(MYSQLI_ASSOC),
];

// Fetch related items for the file
$stmt_degrees = $conn->prepare("SELECT * FROM file_degrees WHERE fid=? ORDER BY created_at DESC");
$stmt_degrees->bind_param("i", $fileId);
$stmt_degrees->execute();
$data['file_degrees'] = $stmt_degrees->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_degrees->close();

$stmt_sessions = $conn->prepare("SELECT * FROM session WHERE session_fid=? ORDER BY session_date DESC");
$stmt_sessions->bind_param("i", $fileId);
$stmt_sessions->execute();
$data['sessions'] = $stmt_sessions->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_sessions->close();

$stmt_tasks = $conn->prepare("SELECT * FROM tasks WHERE file_no=? ORDER BY duedate DESC");
$stmt_tasks->bind_param("i", $fileId);
$stmt_tasks->execute();
$data['tasks'] = $stmt_tasks->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_tasks->close();

$stmt_attachments = $conn->prepare("SELECT * FROM files_attachments WHERE fid=? ORDER BY id DESC");
$stmt_attachments->bind_param("i", $fileId);
$stmt_attachments->execute();
$data['attachments'] = $stmt_attachments->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_attachments->close();

// 4. RENDER PAGE
use App\I18n;
$currentLocale = I18n::getLocale();
?>
<!DOCTYPE html>
<html dir="<?php echo ($currentLocale === 'ar') ? 'rtl' : 'ltr'; ?>" lang="<?php echo $currentLocale; ?>">
<head>
    <title><?php echo __('edit_file_number') . ' ' . safe_output($fileId); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/styles.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/favicon.ico">
</head>
<body style="overflow: auto">
    <div class="container">
        <?php include_once 'sidebar.php'; ?>
        <div class="website">
            <?php include_once 'header.php'; ?>
            <main class="web-page">
                <div class="advinputs-container" id="scrollContainer">
                    <form action="editfile.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="fidget" value="<?php echo safe_output($fileId); ?>">
                        
                        <!-- File Details Section -->
                        <?php include 'partials/_file_details.php'; ?>
                        
                        <!-- Parties (Clients/Opponents) Section -->
                        <?php include 'partials/_file_parties.php'; ?>

                        <!-- Courts and Police Stations Section -->
                        <?php include 'partials/_file_legal_entities.php'; ?>

                        <!-- File Responsible Staff Section -->
                        <?php include 'partials/_file_staff.php'; ?>

                        <!-- Litigation Degrees Section -->
                        <?php include 'partials/_file_degrees.php'; ?>

                        <!-- Sessions Section -->
                        <?php include 'partials/_file_sessions.php'; ?>

                        <!-- Tasks Section -->
                        <?php include 'partials/_file_tasks.php'; ?>

                        <!-- Attachments Section -->
                        <?php include 'partials/_file_attachments.php'; ?>

                        <!-- Form Footer/Submit Button -->
                        <div class="form-footer">
                            <button type="submit" name="save_file" class="green-button"><?php echo __('save_data'); ?></button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    
    <script src="js/file_edit_script.js"></script>
</body>
</html>
