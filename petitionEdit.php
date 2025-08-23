<?php
$pageTitle = 'تعديل الأمر على عريضة';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php'; // Assuming you have a permissions check
include_once 'layout/header.php';

// Check for appropriate permissions, for example:
// if ($row_permcheck['petition_eperm'] != 1) {
//     echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لهذه العملية.</div></div>';
//     include_once 'layout/footer.php';
//     exit();
// }

$petition_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($petition_id === 0) {
    echo '<div class="container mt-5"><div class="alert alert-danger">رقم الأمر غير صحيح.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

$stmt = $conn->prepare("SELECT * FROM petition WHERE id = ?");
$stmt->bind_param("i", $petition_id);
$stmt->execute();
$result = $stmt->get_result();
$petition = $result->fetch_assoc();
$stmt->close();

if (!$petition) {
    echo '<div class="container mt-5"><div class="alert alert-danger">الأمر على عريضة غير موجود.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3><i class="bx bxs-edit-alt"></i> تعديل الأمر على عريضة رقم: <?php echo htmlspecialchars($petition['id']); ?></h3>
        </div>
        <div class="card-body">
            <form action="petition_process_edit.php" method="post">
                <input type="hidden" name="petition_id" value="<?php echo htmlspecialchars($petition['id']); ?>">
                <input type="hidden" name="fid" value="<?php echo htmlspecialchars($petition['fid']); ?>">


                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="petition_date" class="form-label">تاريخ التقديم</label>
                        <input type="date" id="petition_date" name="petition_date" class="form-control" value="<?php echo htmlspecialchars($petition['date']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="petition_type" class="form-label">نوع الطلب</label>
                        <input type="text" id="petition_type" name="petition_type" class="form-control" value="<?php echo htmlspecialchars($petition['type']); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="petition_decision" class="form-label">قرار القاضي</label>
                    <select id="petition_decision" name="petition_decision" class="form-select">
                        <option value="موافقة" <?php echo ($petition['decision'] == 'موافقة') ? 'selected' : ''; ?>>موافقة</option>
                        <option value="رفض" <?php echo ($petition['decision'] == 'رفض') ? 'selected' : ''; ?>>رفض</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="hearing_lastdate" class="form-label">اخر تاريخ لتسجيل قيد الدعوى</label>
                        <input type="date" id="hearing_lastdate" name="hearing_lastdate" class="form-control" value="<?php echo htmlspecialchars($petition['hearing_lastdate']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="appeal_lastdate" class="form-label">اخر تاريخ للتظلم</label>
                        <input type="date" id="appeal_lastdate" name="appeal_lastdate" class="form-control" value="<?php echo htmlspecialchars($petition['appeal_lastdate']); ?>">
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="FileEdit.php?id=<?php echo htmlspecialchars($petition['fid']); ?>" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
