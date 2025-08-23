<?php
$pageTitle = 'مكاتب المحامين';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'AES256.php';
include_once 'layout/header.php'; // Use modern header

// Check for general permission to view the page
if ($row_permcheck['emp_perms_add'] !== '1' && $row_permcheck['emp_perms_edit'] !== '1' && $row_permcheck['emp_perms_delete'] !== '1') {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bxs-institution"></i> مكاتب المحامين</h3>
            <?php if ($row_permcheck['emp_perms_add'] === '1') : ?>
                <a href="lawyerAdd.php" class="btn btn-primary"><i class="bx bx-plus"></i> إضافة مكتب جديد</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>اسم المحامي</th>
                            <th>هاتف متحرك / ثابت</th>
                            <th>نبذة مختصرة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM lawyers WHERE name != '' AND tel_no != '' ORDER BY id DESC");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><?php echo safe_output($row['name']); ?></td>
                                    <td><?php echo safe_output($row['tel_no']); ?></td>
                                    <td><?php echo safe_output($row['about']); ?></td>
                                    <td>
                                        <?php if ($row_permcheck['emp_perms_edit'] === '1') : ?>
                                            <a href="lawyerEdit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="تعديل"><i class="bx bx-edit"></i></a>
                                        <?php endif; ?>
                                        <?php if ($row_permcheck['emp_perms_delete'] === '1') : ?>
                                            <a href="deletelawyer.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد؟');"><i class="bx bx-trash"></i></a>
                                        <?php endif; ?>
                                        <!-- Link to a future attachments page -->
                                        <a href="lawyerAttachments.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-secondary" title="المرفقات"><i class="bx bx-paperclip"></i></a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">لا توجد مكاتب محامين مسجلة.</td></tr>';
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
