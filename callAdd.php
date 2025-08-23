<?php
$pageTitle = 'إضافة مكالمة جديدة';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'layout/header.php';

if ($row_permcheck['call_aperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لهذه العملية.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

$branch = $_GET['branch'] ?? '';
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3><i class="bx bx-phone-plus"></i> إضافة مكالمة جديدة</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="clientsCalls.php">سجل المكالمات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">إضافة جديد</li>
                </ol>
            </nav>
        </div>
        <div class="card-body">
            <form action="call_process_add.php" method="post">
                <input type="hidden" name="branch" value="<?php echo htmlspecialchars($branch); ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="caller_name" class="form-label">اسم المتصل <span class="text-danger">*</span></label>
                        <input type="text" id="caller_name" name="caller_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="caller_no" class="form-label">رقم المتصل <span class="text-danger">*</span></label>
                        <input type="text" id="caller_no" name="caller_no" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="details" class="form-label">تفاصيل المكالمة</label>
                    <textarea id="details" name="details" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="action" class="form-label">الاجراء المتخذ</label>
                    <textarea id="action" name="action" class="form-control" rows="2"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="moved_to" class="form-label">تم تحويل المكالمة إلى</label>
                    <select id="moved_to" name="moved_to" class="form-select">
                        <option value="">-- اختر موظف --</option>
                        <?php
                        $user_query = "SELECT id, name FROM user WHERE signin_perm = 1 ORDER BY name ASC";
                        $user_result = $conn->query($user_query);
                        while ($user = $user_result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($user['id']) . '">' . htmlspecialchars($user['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="text-end mt-4">
                    <a href="clientsCalls.php" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ المكالمة</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
