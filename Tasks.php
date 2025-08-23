<?php
$pageTitle = 'المهام';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'safe_output.php';
include_once 'permissions_check.php';
include_once 'AES256.php';
include_once 'layout/header.php'; // Use modern header

if ($row_permcheck['admjobs_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
?>

<div class="container-fluid mt-4">
    <?php 
    // include_once 'sidebar.php'; // Your sidebar include
    // include_once 'header.php'; // Your visual header bar include
    ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bx-task"></i> المهام</h3>
            <div class="d-flex">
                <a href="AddTask.php" class="btn btn-primary me-2"><i class="bx bx-plus"></i> إضافة مهمة جديدة</a>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        تصنيف
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item" href="Tasks.php?type=all">اجمالي المهام</a></li>
                        <li><a class="dropdown-item" href="Tasks.php?type=inprogress">مهام جارى العمل عليها</a></li>
                        <li><a class="dropdown-item" href="Tasks.php?type=pending">مهام لم يتخذ بها إجراء</a></li>
                        <li><a class="dropdown-item" href="Tasks.php?type=finished">مهام منتهية</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>رقم الملف</th>
                            <th>ت.التنفيذ</th>
                            <th>المهمة</th>
                            <th>التفاصيل</th>
                            <th>الأهمية</th>
                            <th>الموظف المكلف</th>
                            <th>الحالة</th>
                            <th class="no-print">الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $type = $_GET['type'] ?? 'all';
                        $status_filter = '';
                        switch ($type) {
                            case 'pending':
                                $status_filter = ' WHERE task_status = 0';
                                break;
                            case 'inprogress':
                                $status_filter = ' WHERE task_status = 1';
                                break;
                            case 'finished':
                                $status_filter = ' WHERE task_status = 2';
                                break;
                            default:
                                $status_filter = ''; // 'all'
                                break;
                        }

                        $stmt = $conn->prepare("SELECT * FROM tasks $status_filter ORDER BY duedate DESC");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $counttsk = 0;
                            while ($rowtsk = $result->fetch_assoc()) {
                                $counttsk++;
                                $status_class = '';
                                $status_text = 'لم يبدأ';
                                if ($rowtsk['task_status'] == 1) {
                                    $status_class = 'table-warning';
                                    $status_text = 'جاري العمل';
                                } else if ($rowtsk['task_status'] == 2) {
                                    $status_class = 'table-success';
                                    $status_text = 'منتهية';
                                }
                        ?>
                                <tr class="<?php echo $status_class; ?>">
                                    <td><?php echo $counttsk; ?></td>
                                    <td>
                                        <?php if ($rowtsk['file_no'] != 0) {
                                            echo '<a href="FileEdit.php?id=' . safe_output($rowtsk['file_no']) . '">' . safe_output($rowtsk['file_no']) . '</a>';
                                        } ?>
                                    </td>
                                    <td><?php echo safe_output($rowtsk['duedate']); ?></td>
                                    <td>
                                        <?php
                                        // Fetch task type name
                                        $tskkid = $rowtsk['task_type'];
                                        $stmt_task_type = $conn->prepare("SELECT job_name FROM job_name WHERE id=?");
                                        $stmt_task_type->bind_param("i", $tskkid);
                                        $stmt_task_type->execute();
                                        $result_task_type = $stmt_task_type->get_result();
                                        if ($row_task_type = $result_task_type->fetch_assoc()) {
                                            echo safe_output($row_task_type['job_name']);
                                        }
                                        $stmt_task_type->close();
                                        ?>
                                    </td>
                                    <td><?php echo safe_output($rowtsk['details']); ?></td>
                                    <td>
                                        <?php
                                        if ($rowtsk['priority'] == 1) {
                                            echo '<span class="badge bg-danger">عاجل</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">عادي</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        // Fetch employee name
                                        $empppid = $rowtsk['employee_id'];
                                        $stmt_emp = $conn->prepare("SELECT name FROM user WHERE id=?");
                                        $stmt_emp->bind_param("i", $empppid);
                                        $stmt_emp->execute();
                                        $result_emp = $stmt_emp->get_result();
                                        if ($row_emp = $result_emp->fetch_assoc()) {
                                            echo safe_output($row_emp['name']);
                                        }
                                        $stmt_emp->close();
                                        ?>
                                    </td>
                                    <td><?php echo $status_text; ?></td>
                                    <td class="no-print">
                                        <a href="EditTask.php?id=<?php echo safe_output($rowtsk['id']); ?>" class="btn btn-sm btn-info" title="تعديل"><i class="bx bx-edit"></i></a>
                                        <a href="tdel.php?tid=<?php echo safe_output($rowtsk['id']); ?>" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد؟');"><i class="bx bx-trash"></i></a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="9" class="text-center">لا توجد مهام.</td></tr>';
                        }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; // Use modern footer ?>
