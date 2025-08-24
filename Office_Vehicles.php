<?php
// FILE: Office_Vehicles.php

/**
 * This page is for managing the office's vehicle fleet.
 * It allows viewing, adding, editing, and deleting vehicle records.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
$pageTitle = 'الموارد و المركبات';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'layout/header.php';

// 2. PERMISSIONS CHECK
// =============================================================================
$can_view = $row_permcheck['emp_perms_read'] == 1; // Assuming this perm is for vehicles
$can_add = $row_permcheck['emp_perms_add'] == 1;
$can_edit = $row_permcheck['emp_perms_edit'] == 1;
$can_delete = $row_permcheck['emp_perms_delete'] == 1;

if (!$can_view) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// 3. DATA FETCHING & INITIALIZATION
// =============================================================================
$page_mode = 'list';
$edit_data = null;

// Fetch all users for dropdowns and list display (performance optimization)
$users = [];
$user_result = $conn->query("SELECT id, name FROM user ORDER BY name ASC");
if ($user_result) {
    while ($user = $user_result->fetch_assoc()) {
        $users[$user['id']] = $user['name'];
    }
}

// Check if we are in edit mode
if (isset($_GET['edit']) && $can_edit) {
    $page_mode = 'edit';
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} elseif (isset($_GET['addmore']) && $can_add) {
    $page_mode = 'add';
}

// Fetch all vehicles for the main table display
$vehicles = [];
$stmt = $conn->prepare("SELECT * FROM vehicles ORDER BY id DESC");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
    $stmt->close();
}


// 4. RENDER PAGE
// =============================================================================
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bxs-car"></i> الموارد و المركبات</h3>
            <?php if ($can_add) : ?>
                <button class="btn btn-primary" onclick="openModal('addEditVehicleModal')"><i class="bx bx-plus"></i> إضافة مركبة جديدة</button>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>عهدة الموظف</th>
                            <th>الفرع</th>
                            <th>نوع السيارة</th>
                            <th>رقم السيارة</th>
                            <th>انتهاء الملكية</th>
                            <th>انتهاء التأمين</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($vehicles)): ?>
                            <tr><td colspan="7" class="text-center">لا توجد مركبات مسجلة.</td></tr>
                        <?php else: ?>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <tr>
                                    <td><?php echo safe_output($users[$vehicle['emp_id']] ?? 'غير محدد'); ?></td>
                                    <td><?php echo safe_output($vehicle['branch']); ?></td>
                                    <td><?php echo safe_output($vehicle['type'] . ' - ' . $vehicle['model']); ?></td>
                                    <td><?php echo safe_output($vehicle['num']); ?></td>
                                    <td><?php echo safe_output($vehicle['lic_expir']); ?></td>
                                    <td><?php echo safe_output($vehicle['insur_expir']); ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($vehicle['photo'])): ?>
                                            <a href="<?php echo safe_output($vehicle['photo']); ?>" target="_blank" class="btn btn-sm btn-secondary" title="عرض الصورة"><i class="bx bx-photo-album"></i></a>
                                        <?php endif; ?>
                                        <?php if ($can_edit): ?>
                                            <a href="?edit=1&id=<?php echo $vehicle['id']; ?>" class="btn btn-sm btn-info" title="تعديل"><i class="bx bx-edit"></i></a>
                                        <?php endif; ?>
                                        <?php if ($can_delete): ?>
                                            <a href="deletevehicle.php?id=<?php echo $vehicle['id']; ?>" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد؟');"><i class="bx bx-trash"></i></a>
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

<!-- Add/Edit Vehicle Modal -->
<div id="addEditVehicleModal" class="modal-overlay" style="display: <?php echo ($page_mode === 'add' || $page_mode === 'edit') ? 'block' : 'none'; ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h4><?php echo ($page_mode === 'edit') ? 'تعديل بيانات المركبة' : 'إضافة مركبة جديدة'; ?></h4>
            <a href="Office_Vehicles.php" class="close-button">&times;</a>
        </header>
        <form action="<?php echo ($page_mode === 'edit') ? 'vehedit.php' : 'vehicleadd.php'; ?>" method="post" enctype="multipart/form-data">
            <?php if ($page_mode === 'edit'): ?>
                <input type="hidden" name="id" value="<?php echo safe_output($edit_data['id']); ?>">
            <?php endif; ?>
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="emp_id">عهدة الموظف <span class="required">*</span></label>
                        <select id="emp_id" name="emp_id" class="form-input" required>
                            <option value="">-- اختر موظف --</option>
                            <?php foreach($users as $id => $name): ?>
                                <option value="<?php echo $id; ?>" <?php if (($edit_data['emp_id'] ?? '') == $id) echo 'selected'; ?>>
                                    <?php echo safe_output($name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                     <div class="form-group">
                        <label for="type">نوع السيارة <span class="required">*</span></label>
                        <input id="type" name="type" type="text" class="form-input" value="<?php echo safe_output($edit_data['type'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="model">موديل السيارة</label>
                        <input id="model" name="model" type="text" class="form-input" value="<?php echo safe_output($edit_data['model'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="num">رقم السيارة</label>
                        <input id="num" name="num" type="text" class="form-input" value="<?php echo safe_output($edit_data['num'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="lic_expir">انتهاء الملكية</label>
                        <input id="lic_expir" name="lic_expir" type="date" class="form-input" value="<?php echo safe_output($edit_data['lic_expir'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="insur_expir">انتهاء التأمين</label>
                        <input id="insur_expir" name="insur_expir" type="date" class="form-input" value="<?php echo safe_output($edit_data['insur_expir'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="branch">الفرع</label>
                         <select id="branch" name="branch" class="form-input">
                            <option value="الشارقة" <?php if (($edit_data['branch'] ?? '') === 'الشارقة') echo 'selected'; ?>>الشارقة</option>
                            <option value="دبي" <?php if (($edit_data['branch'] ?? '') === 'دبي') echo 'selected'; ?>>دبي</option>
                            <option value="عجمان" <?php if (($edit_data['branch'] ?? '') === 'عجمان') echo 'selected'; ?>>عجمان</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label for="notes">ملاحظات</label>
                        <textarea id="notes" name="notes" class="form-input" rows="3"><?php echo safe_output($edit_data['notes'] ?? ''); ?></textarea>
                    </div>
                     <div class="form-group full-width">
                        <label for="photo">صورة المركبة</label>
                        <input id="photo" name="photo" type="file" class="form-input">
                        <?php if (!empty($edit_data['photo'])): ?>
                            <p class="current-file-info">الملف الحالي: <a href="<?php echo safe_output($edit_data['photo']); ?>" target="_blank"><?php echo basename(safe_output($edit_data['photo'])); ?></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <footer class="modal-footer">
                <button type="submit" class="form-btn submit-btn">حفظ</button>
                <a href="Office_Vehicles.php" class="form-btn cancel-btn">إلغاء</a>
            </footer>
        </form>
    </div>
</div>

<script src="js/popups.js"></script>
<?php include_once 'layout/footer.php'; ?>
