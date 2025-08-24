<?php
// FILE: Business_Management.php

/**
 * Page for adding and editing administrative tasks related to a specific case file.
 *
 * This file handles:
 * 1. Displaying a form to create or update a task.
 * 2. Listing all existing administrative tasks for the given file.
 *
 * GET Params:
 * - fid: The file ID (required, integer).
 * - tid: The task ID for editing (optional, integer).
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
include_once 'connection.php';
include_once 'login_check.php'; // Assumes this sets up the user session
include_once 'safe_output.php'; // For the safe_output function

// 2. INPUT VALIDATION & INITIALIZATION
// =============================================================================
$fileId = isset($_GET['fid']) ? (int)$_GET['fid'] : 0;
$taskId = isset($_GET['tid']) ? (int)$_GET['tid'] : 0;
$isEditMode = $taskId > 0;

if ($fileId <= 0) {
    header("Location: index.php");
    exit();
}

// 3. DATA FETCHING & PROCESSING
// =============================================================================

// Fetch File and Client Details
$stmt = $conn->prepare("
    SELECT f.file_id, f.file_subject, f.frelated_place, c.arname as client_name
    FROM file f
    JOIN client c ON f.file_client = c.id
    WHERE f.file_id = ?
");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$fileDetails = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$fileDetails) {
    die("File not found.");
}

// Fetch Legal Researchers (Job Title ID 11)
$legalResearchers = [];
$stmt = $conn->prepare("SELECT id, name FROM user WHERE job_title = '11'");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $legalResearchers[] = $row;
}
$stmt->close();

// Fetch Job Types
$jobTypes = [];
$stmt = $conn->prepare("SELECT id, job_name FROM job_name");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $jobTypes[] = $row;
}
$stmt->close();

// Fetch File Degrees
$fileDegrees = [];
$stmt = $conn->prepare("SELECT id, case_num, file_year, degree FROM file_degrees WHERE fid = ?");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $fileDegrees[] = $row;
}
$stmt->close();

// Fetch Existing Tasks for this File
$existingTasks = [];
$stmt = $conn->prepare("
    SELECT 
        t.id, t.degree, t.task_type, t.employee_id, t.duedate, t.details, t.priority, t.task_status, t.timestamp,
        u_resp.name as responsible_name,
        u_emp.name as employee_name,
        jn.job_name,
        fd.case_num, fd.file_year, fd.degree as degree_name
    FROM tasks t
    LEFT JOIN user u_resp ON t.responsible = u_resp.id
    LEFT JOIN user u_emp ON t.employee_id = u_emp.id
    LEFT JOIN job_name jn ON t.task_type = jn.id
    LEFT JOIN file_degrees fd ON t.degree = fd.id
    WHERE t.file_no = ? ORDER BY t.timestamp DESC
");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $existingTasks[] = $row;
}
$stmt->close();

// Task to Edit Details
$taskToEdit = [
    'employee_id' => '',
    'task_type' => '',
    'degree' => '',
    'priority' => '0', // Default to 'Normal'
    'duedate' => date("Y-m-d"),
    'details' => ''
];

if ($isEditMode) {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $taskToEdit = $result->fetch_assoc();
    }
    $stmt->close();
}

// Helper function for file prefix
function getFilePrefix($place) {
    switch ($place) {
        case 'الشارقة': return 'SHJ';
        case 'دبي': return 'DXB';
        case 'عجمان': return 'AJM';
        default: return '';
    }
}

// 4. PREPARE VARIABLES FOR VIEW
// =============================================================================
$formAction = $isEditMode ? 'taedit.php' : 'tadd.php';
$pageTitle = $isEditMode ? 'تعديل عمل إداري' : 'إضافة عمل إداري';
$submitButtonText = $isEditMode ? 'حفظ + تعديل البيانات' : 'حفظ وتخزين البيانات';

?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo safe_output($pageTitle); ?></title>
    <link rel="stylesheet" href="css/sites.css">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; }
        .button { padding: 5px 15px; cursor: pointer; }
        .table-tasks { font-size: 16px; width: 100%; border-collapse: collapse; background-color: #FFFFFF; }
        .table-tasks th, .table-tasks td { border: 1px solid #c0b89c; padding: 8px; text-align: center; }
        .table-tasks thead tr { background-color: #96693e; color: #FFF; height: 40px; }
        .status-0 { background-color: #FDD0D0 !important; } /* Not Started */
        .status-1 { background-color: #FFFF00 !important; } /* In Progress */
        .status-2 { background-color: #B7F9A6 !important; } /* Completed */
        .urgent-icon { width: 30px; }
        .action-icon { cursor: pointer; }
    </style>
</head>
<body>

<form name="TaskForm" action="<?php echo $formAction; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="job_fid" value="<?php echo safe_output($fileId); ?>">
    <?php if ($isEditMode): ?>
        <input type="hidden" name="tid" value="<?php echo safe_output($taskId); ?>">
    <?php endif; ?>

    <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center" bgcolor="#FFFFFF">
        <thead>
            <tr>
                <th width="16%">&nbsp;</th>
                <th width="58%" align="right"><b><font color="#4b1807" style="font-size:16px">الاعمال الإدارية</font></b></th>
                <th width="26%" rowspan="8" align="center" valign="top">
                    <p>الموكل: <?php echo safe_output($fileDetails['client_name']); ?></p>
                    <p>الموضوع: <?php echo safe_output($fileDetails['file_subject']); ?></p>
                </th>
            </tr>
            <tr>
                <th align="left">رقم الملف :</th>
                <th align="right" dir="ltr" style="font-size:18px; color:#00F">
                    <font color="#FF0000"><?php echo safe_output(getFilePrefix($fileDetails['frelated_place'])); ?></font>
                    <?php echo safe_output($fileDetails['file_id']); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th align="left"><label for="flegal_researcher">الباحث القانوني المكلف :</label></th>
                <td align="right">
                    <select name="flegal_researcher" id="flegal_researcher" dir="rtl" style="width:50%;" required>
                        <option value="">-- اختر --</option>
                        <?php foreach ($legalResearchers as $researcher): ?>
                            <option value="<?php echo safe_output($researcher['id']); ?>" <?php echo ($researcher['id'] == $taskToEdit['employee_id']) ? 'selected' : ''; ?>>
                                <?php echo safe_output($researcher['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <font color="#FF0000">*</font>
                </td>
            </tr>
            <tr>
                <th align="left"><label for="type_name">نوع العمل :</label></th>
                <td align="right">
                    <select name="type_name" id="type_name" dir="rtl" style="width:50%;" required>
                         <option value="">-- اختر --</option>
                        <?php foreach ($jobTypes as $job): ?>
                            <option value="<?php echo safe_output($job['id']); ?>" <?php echo ($job['id'] == $taskToEdit['task_type']) ? 'selected' : ''; ?>>
                                <?php echo safe_output($job['job_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <font color="#FF0000">*</font>
                </td>
            </tr>
            <tr>
                <th align="left"><label for="degree_id">درجة التقاضي :</label></th>
                <td align="right">
                    <select name="degree_id" id="degree_id" dir="rtl" style="width:50%;">
                        <option value="0">-- لا يوجد --</option>
                        <?php foreach ($fileDegrees as $degree): ?>
                            <option value="<?php echo safe_output($degree['id']); ?>" <?php echo ($degree['id'] == $taskToEdit['degree']) ? 'selected' : ''; ?>>
                                <?php echo safe_output("{$degree['case_num']}/{$degree['file_year']}-{$degree['degree']}"); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th align="left">الاهمية :</th>
                <td align="right">
                    <label><input type="radio" name="priority" value="0" style="border:none" <?php echo ($taskToEdit['priority'] == '0') ? 'checked' : ''; ?>> <font color="#009900">عادي</font></label>
                    <label><input type="radio" name="priority" value="1" style="border:none" <?php echo ($taskToEdit['priority'] == '1') ? 'checked' : ''; ?>> <font color="#FF0000">عاجل</font></label>
                </td>
            </tr>
            <tr>
                <th align="left"><label for="date">تاريخ تنفيذ العمل :</label></th>
                <td align="right">
                    <input type="date" name="date" id="date" value="<?php echo safe_output($taskToEdit['duedate']); ?>" required>
                </td>
            </tr>
            <tr>
                <th align="left" valign="top"><label for="details">التفاصيل:</label></th>
                <td align="right">
                    <textarea dir="rtl" id="details" wrap="physical" rows="3" style="width:98%" name="details"><?php echo safe_output($taskToEdit['details']); ?></textarea>
                </td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td align="right">
                    <input type="submit" value="<?php echo safe_output($submitButtonText); ?>" class="button">
                    <input type="button" value="مسح الحقول" class="button" onclick="location.href='Business_Management.php?fid=<?php echo safe_output($fileId); ?>'">
                </td>
            </tr>
        </tbody>
    </table>
</form>

<br />

<table class="table-tasks">
    <thead>
        <tr>
            <th>درجة التقاضي</th>
            <th>نوع العمل الإداري</th>
            <th>الموظف المكلف</th>
            <th>ت.التكليف</th>
            <th>التفاصيل</th>
            <th>م.ت/ الإدخال</th>
            <th>تعديل</th>
            <th>حذف</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($existingTasks)): ?>
            <tr>
                <td colspan="8">لا توجد أعمال إدارية مسجلة لهذا الملف.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($existingTasks as $task): ?>
                <tr class="status-<?php echo safe_output($task['task_status']); ?>">
                    <td><?php echo safe_output($task['case_num'] . '/' . $task['file_year'] . '-' . $task['degree_name']); ?></td>
                    <td><?php echo safe_output($task['job_name']); ?></td>
                    <td>
                        <?php echo safe_output($task['employee_name']); ?>
                        <?php if ($task['priority'] == '1'): ?>
                            <br><img src="images/urgent2-300x220.jpg" alt="Urgent" class="urgent-icon">
                        <?php endif; ?>
                    </td>
                    <td><?php echo safe_output($task['duedate']); ?></td>
                    <td align="right"><?php echo safe_output($task['details']); ?></td>
                    <td><?php echo safe_output($task['timestamp'] . ' ' . $task['responsible_name']); ?></td>
                    <td>
                        <a href="Business_Management.php?fid=<?php echo safe_output($fileId); ?>&tid=<?php echo safe_output($task['id']); ?>">
                            <img src="images/EditB.png" alt="Edit" border="0" class="action-icon">
                        </a>
                    </td>
                    <td>
                        <a href="tdel.php?fid=<?php echo safe_output($fileId); ?>&tid=<?php echo safe_output($task['id']); ?>" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا البند؟');">
                            <img src="images/delete.png" alt="Delete" border="0" class="action-icon">
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
