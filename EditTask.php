<?php
$pageTitle = 'تعديل المهمة';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'layout/header.php';

// Ensure user has permission to edit tasks
if ($row_permcheck['admjobs_eperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لهذه العملية.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// Fetch the task data for editing
$task_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($task_id === 0) {
    echo '<div class="container mt-5"><div class="alert alert-danger">رقم المهمة غير صحيح.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();
$stmt->close();

if (!$task) {
    echo '<div class="container mt-5"><div class="alert alert-danger">المهمة غير موجودة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>تعديل المهمة رقم: <?php echo htmlspecialchars($task['id']); ?></h3>
        </div>
        <div class="card-body">
            <form action="task_process_edit.php" method="post">
                <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">

                <div class="mb-3">
                    <label for="employee_id" class="form-label">الموظف المكلف</label>
                    <select id="employee_id" name="employee_id" class="form-select" required>
                        <?php
                        $user_query = "SELECT id, name FROM user ORDER BY name ASC";
                        $user_result = $conn->query($user_query);
                        while ($user = $user_result->fetch_assoc()) {
                            $selected = ($user['id'] == $task['employee_id']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($user['id']) . '" ' . $selected . '>' . htmlspecialchars($user['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="task_type" class="form-label">نوع المهمة</label>
                    <select id="task_type" name="task_type" class="form-select" required>
                         <?php
                        $job_query = "SELECT id, job_name FROM job_name ORDER BY job_name ASC";
                        $job_result = $conn->query($job_query);
                        while ($job = $job_result->fetch_assoc()) {
                            $selected = ($job['id'] == $task['task_type']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($job['id']) . '" ' . $selected . '>' . htmlspecialchars($job['job_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="details" class="form-label">التفاصيل</label>
                    <textarea id="details" name="details" class="form-control" rows="3"><?php echo htmlspecialchars($task['details']); ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="duedate" class="form-label">تاريخ التنفيذ</label>
                        <input type="date" id="duedate" name="duedate" class="form-control" value="<?php echo htmlspecialchars($task['duedate']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="priority" class="form-label">الأهمية</label>
                        <select id="priority" name="priority" class="form-select">
                            <option value="0" <?php echo ($task['priority'] == '0') ? 'selected' : ''; ?>>عادي</option>
                            <option value="1" <?php echo ($task['priority'] == '1') ? 'selected' : ''; ?>>عاجل</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <a href="Tasks.php" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
