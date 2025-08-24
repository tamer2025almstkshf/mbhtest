<?php
// FILE: EditTask.php

/**
 * Page for editing an existing administrative task.
 *
 * This script fetches task data, populates a form for editing,
 * and handles user permissions for the action.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
$pageTitle = 'تعديل المهمة';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';

// 2. PERMISSIONS CHECK
// =============================================================================
// Ensure user has permission to edit administrative jobs.
if ($row_permcheck['admjobs_eperm'] !== 1) {
    // A more user-friendly error display
    include_once 'layout/header.php';
    echo '<div class="container mt-5"><div class="alert alert-danger" role="alert"><strong>خطأ:</strong> ليس لديك الصلاحية اللازمة للقيام بهذه العملية.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// 3. DATA FETCHING & VALIDATION
// =============================================================================
$task_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($task_id === 0) {
    include_once 'layout/header.php';
    echo '<div class="container mt-5"><div class="alert alert-danger" role="alert"><strong>خطأ:</strong> رقم المهمة غير صحيح.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// Fetch the task to be edited
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();
$stmt->close();

if (!$task) {
    include_once 'layout/header.php';
    echo '<div class="container mt-5"><div class="alert alert-danger" role="alert"><strong>خطأ:</strong> المهمة المطلوبة غير موجودة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// Fetch users for the dropdown
$users = [];
$user_result = $conn->query("SELECT id, name FROM user ORDER BY name ASC");
while ($user = $user_result->fetch_assoc()) {
    $users[] = $user;
}

// Fetch job types for the dropdown
$job_types = [];
$job_result = $conn->query("SELECT id, job_name FROM job_name ORDER BY job_name ASC");
while ($job = $job_result->fetch_assoc()) {
    $job_types[] = $job;
}

// 4. RENDER PAGE
// =============================================================================
include_once 'layout/header.php';
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3>تعديل المهمة رقم: <?php echo safe_output($task['id']); ?></h3>
        </div>
        <div class="card-body">
            <form action="task_process_edit.php" method="post">
                <input type="hidden" name="task_id" value="<?php echo safe_output($task['id']); ?>">

                <div class="mb-3">
                    <label for="employee_id" class="form-label">الموظف المكلف</label>
                    <select id="employee_id" name="employee_id" class="form-select" required>
                        <option value="">-- اختر موظف --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo safe_output($user['id']); ?>" <?php echo ($user['id'] == $task['employee_id']) ? 'selected' : ''; ?>>
                                <?php echo safe_output($user['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="task_type" class="form-label">نوع المهمة</label>
                    <select id="task_type" name="task_type" class="form-select" required>
                         <option value="">-- اختر نوع المهمة --</option>
                        <?php foreach ($job_types as $job): ?>
                            <option value="<?php echo safe_output($job['id']); ?>" <?php echo ($job['id'] == $task['task_type']) ? 'selected' : ''; ?>>
                                <?php echo safe_output($job['job_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="details" class="form-label">التفاصيل</label>
                    <textarea id="details" name="details" class="form-control" rows="3"><?php echo safe_output($task['details']); ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="duedate" class="form-label">تاريخ التنفيذ</label>
                        <input type="date" id="duedate" name="duedate" class="form-control" value="<?php echo safe_output($task['duedate']); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="priority" class="form-label">الأهمية</label>
                        <select id="priority" name="priority" class="form-select">
                            <option value="0" <?php echo ($task['priority'] == '0') ? 'selected' : ''; ?>>عادي</option>
                            <option value="1" <?php echo ($task['priority'] == '1') ? 'selected' : ''; ?>>عاجل</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="task_status" class="form-label">حالة المهمة</label>
                        <select id="task_status" name="task_status" class="form-select">
                            <option value="0" <?php echo ($task['task_status'] == '0') ? 'selected' : ''; ?>>لم يبدأ</option>
                            <option value="1" <?php echo ($task['task_status'] == '1') ? 'selected' : ''; ?>>جاري العمل</option>
                            <option value="2" <?php echo ($task['task_status'] == '2') ? 'selected' : ''; ?>>منتهية</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">إضافة ملاحظة</label>
                    <textarea id="note" name="note" class="form-control" rows="2" placeholder="أضف ملاحظة حول التحديث إذا لزم الأمر..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="Tasks.php" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
