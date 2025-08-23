<?php
$pageTitle = 'تعديل المكالمة';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'layout/header.php';

if ($row_permcheck['call_eperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لهذه العملية.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

$call_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($call_id === 0) {
    echo '<div class="container mt-5"><div class="alert alert-danger">رقم المكالمة غير صحيح.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

$stmt = $conn->prepare("SELECT * FROM clientsCalls WHERE id = ?");
$stmt->bind_param("i", $call_id);
$stmt->execute();
$result = $stmt->get_result();
$call = $result->fetch_assoc();
$stmt->close();

if (!$call) {
    echo '<div class="container mt-5"><div class="alert alert-danger">المكالمة غير موجودة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3><i class="bx bxs-edit"></i> تعديل المكالمة من: <?php echo htmlspecialchars($call['caller_name']); ?></h3>
        </div>
        <div class="card-body">
            <form action="call_process_edit.php" method="post">
                <input type="hidden" name="call_id" value="<?php echo htmlspecialchars($call['id']); ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="caller_name" class="form-label">اسم المتصل <span class="text-danger">*</span></label>
                        <input type="text" id="caller_name" name="caller_name" class="form-control" value="<?php echo htmlspecialchars($call['caller_name']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="caller_no" class="form-label">رقم المتصل <span class="text-danger">*</span></label>
                        <input type="text" id="caller_no" name="caller_no" class="form-control" value="<?php echo htmlspecialchars($call['caller_no']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="details" class="form-label">تفاصيل المكالمة</label>
                    <textarea id="details" name="details" class="form-control" rows="3"><?php echo htmlspecialchars($call['details']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="action" class="form-label">الاجراء المتخذ</label>
                    <textarea id="action" name="action" class="form-control" rows="2"><?php echo htmlspecialchars($call['action']); ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="moved_to" class="form-label">تم تحويل المكالمة إلى</label>
                    <select id="moved_to" name="moved_to" class="form-select">
                        <option value="">-- اختر موظف --</option>
                        <?php
                        $user_query = "SELECT id, name FROM user WHERE signin_perm = 1 ORDER BY name ASC";
                        $user_result = $conn->query($user_query);
                        while ($user = $user_result->fetch_assoc()) {
                            $selected = ($user['id'] == $call['moved_to']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($user['id']) . '" ' . $selected . '>' . htmlspecialchars($user['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="text-end mt-4">
                    <a href="clientsCalls.php" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
