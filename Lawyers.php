<?php
// FILE: Lawyers.php

/**
 * This page displays a list of external lawyers or law offices.
 * It allows users with appropriate permissions to view, add, edit,
 * and delete lawyer records.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
$pageTitle = 'مكاتب المحامين';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
// AES256.php is included but not used in this file, so it could be removed.
// include_once 'AES256.php'; 
include_once 'layout/header.php';

// 2. PERMISSIONS CHECK
// =============================================================================
$can_view = $row_permcheck['emp_perms_view'] === '1'; // Assuming a view permission exists
$can_add = $row_permcheck['emp_perms_add'] === '1';
$can_edit = $row_permcheck['emp_perms_edit'] === '1';
$can_delete = $row_permcheck['emp_perms_delete'] === '1';

// If the user has no permissions for this module, deny access.
if (!$can_view && !$can_add && !$can_edit && !$can_delete) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// 3. DATA FETCHING
// =============================================================================
$lawyers = [];
$stmt = $conn->prepare("SELECT id, name, tel_no, about FROM lawyers WHERE name != '' AND tel_no != '' ORDER BY id DESC");
// It's good practice to check if the statement was prepared successfully
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $lawyers[] = $row;
    }
    $stmt->close();
} else {
    // Handle potential database errors
    error_log("Failed to prepare statement in Lawyers.php: " . $conn->error);
}


// 4. RENDER PAGE
// =============================================================================
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bxs-institution"></i> مكاتب المحامين</h3>
            <?php if ($can_add) : ?>
                <a href="lawyerAdd.php" class="btn btn-primary"><i class="bx bx-plus"></i> إضافة مكتب جديد</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>اسم المحامي</th>
                            <th>هاتف متحرك / ثابت</th>
                            <th>نبذة مختصرة</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lawyers)): ?>
                            <tr>
                                <td colspan="4" class="text-center">لا توجد مكاتب محامين مسجلة.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lawyers as $lawyer): ?>
                                <tr>
                                    <td><?php echo safe_output($lawyer['name']); ?></td>
                                    <td><?php echo safe_output($lawyer['tel_no']); ?></td>
                                    <td><?php echo safe_output($lawyer['about']); ?></td>
                                    <td class="text-center">
                                        <!-- Attachments Button -->
                                        <a href="lawyerAttachments.php?id=<?php echo $lawyer['id']; ?>" class="btn btn-sm btn-secondary" title="المرفقات"><i class="bx bx-paperclip"></i></a>
                                        
                                        <?php if ($can_edit) : ?>
                                            <a href="lawyerEdit.php?id=<?php echo $lawyer['id']; ?>" class="btn btn-sm btn-info" title="تعديل"><i class="bx bx-edit"></i></a>
                                        <?php endif; ?>
                                        
                                        <?php if ($can_delete) : ?>
                                            <a href="deletelawyer.php?id=<?php echo $lawyer['id']; ?>" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا السجل؟');"><i class="bx bx-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
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
