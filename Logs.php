<?php
// FILE: Logs.php

/**
 * This page displays the application's activity logs, showing actions
 * performed by different users.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
$pageTitle = 'سجلات البرنامج';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'layout/header.php';

// 2. PERMISSIONS CHECK
// =============================================================================
$can_view_logs = ($row_permcheck['logs_rperm'] === '1');
$can_delete_logs = ($row_permcheck['logs_dperm'] === '1');

if (!$can_view_logs) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// 3. DATA FETCHING (Optimized)
// =============================================================================

// Fetch all users into an associative array for quick lookup (id => name).
// This avoids running a query for each log entry (N+1 problem).
$users = [];
$user_result = $conn->query("SELECT id, name FROM user");
if ($user_result) {
    while ($user = $user_result->fetch_assoc()) {
        $users[$user['id']] = $user['name'];
    }
}

// Fetch all logs.
$logs = [];
$stmt = $conn->prepare("SELECT id, empid, action, timestamp FROM logs ORDER BY id DESC");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
    $stmt->close();
} else {
    error_log("Failed to prepare statement in Logs.php: " . $conn->error);
}


// 4. RENDER PAGE
// =============================================================================
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bx-history"></i> سجلات البرنامج</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="logsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>اسم الموظف</th>
                            <th>العمل</th>
                            <th>الوقت والتاريخ</th>
                            <?php if ($can_delete_logs): ?>
                                <th class="text-center">الإجراءات</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="<?php echo $can_delete_logs ? '4' : '3'; ?>" class="text-center">لا توجد سجلات لعرضها.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td>
                                        <?php
                                        // Look up user name from the pre-fetched array
                                        $employee_name = $users[$log['empid']] ?? 'مستخدم غير معروف';
                                        echo safe_output($employee_name);
                                        ?>
                                    </td>
                                    <td><?php echo safe_output($log['action']); ?></td>
                                    <td>
                                        <?php
                                        // Format timestamp for better readability
                                        $timestamp = strtotime($log['timestamp']);
                                        echo date('Y-m-d h:i A', $timestamp);
                                        ?>
                                    </td>
                                    <?php if ($can_delete_logs): ?>
                                    <td class="text-center">
                                        <a href="deletelog.php?id=<?php echo safe_output($log['id']); ?>" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا السجل؟');">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
