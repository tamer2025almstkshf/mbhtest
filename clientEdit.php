<?php
$pageTitle = 'تعديل بيانات العميل';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'layout/header.php';

// Check for permission to edit clients
if ($row_permcheck['clients_eperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لهذه العملية.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// Fetch the client data for editing
$client_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($client_id === 0) {
    echo '<div class="container mt-5"><div class="alert alert-danger">رقم العميل غير صحيح.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

$stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();
$stmt->close();

if (!$client) {
    echo '<div class="container mt-5"><div class="alert alert-danger">العميل غير موجود.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3><i class="bx bxs-edit"></i> تعديل بيانات العميل: <?php echo htmlspecialchars($client['arname']); ?></h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="clients.php">العملاء</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تعديل</li>
                </ol>
            </nav>
        </div>
        <div class="card-body">
            <form action="client_process_edit.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="client_id" value="<?php echo htmlspecialchars($client['id']); ?>">

                <h4>المعلومات الأساسية</h4>
                <hr>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="arname" class="form-label">الإسم (عربي) <span class="text-danger">*</span></label>
                        <input type="text" id="arname" name="arname" class="form-control" value="<?php echo htmlspecialchars($client['arname']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="engname" class="form-label">الإسم (إنجليزي)</label>
                        <input type="text" id="engname" name="engname" class="form-control" value="<?php echo htmlspecialchars($client['engname']); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="client_kind" class="form-label">التصنيف <span class="text-danger">*</span></label>
                        <select id="client_kind" name="client_kind" class="form-select" required>
                            <option value="موكل" <?php if ($client['client_kind'] == 'موكل') echo 'selected'; ?>>موكل</option>
                            <option value="خصم" <?php if ($client['client_kind'] == 'خصم') echo 'selected'; ?>>خصم</option>
                            <option value="عناوين هامة" <?php if ($client['client_kind'] == 'عناوين هامة') echo 'selected'; ?>>عناوين هامة</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="client_type" class="form-label">الفئة <span class="text-danger">*</span></label>
                        <select id="client_type" name="client_type" class="form-select" required>
                            <option value="شخص" <?php if ($client['client_type'] == 'شخص') echo 'selected'; ?>>شخص</option>
                            <option value="شركة" <?php if ($client['client_type'] == 'شركة') echo 'selected'; ?>>شركة</option>
                            <option value="حكومة / مؤسسات" <?php if ($client['client_type'] == 'حكومة / مؤسسات') echo 'selected'; ?>>حكومة / مؤسسات</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label">الجنسية</label>
                        <select id="country" name="country" class="form-select">
                            <option value="">-- اختر الجنسية --</option>
                            <?php
                            $country_query = "SELECT name FROM countries ORDER BY name ASC";
                            $country_result = $conn->query($country_query);
                            if ($country_result->num_rows > 0) {
                                while ($country_row = $country_result->fetch_assoc()) {
                                    $country_name = htmlspecialchars($country_row['name']);
                                    $selected = ($client['country'] == $country_name) ? 'selected' : '';
                                    echo "<option value=\"$country_name\" $selected>$country_name</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <h4 class="mt-4">معلومات الاتصال</h4>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tel1" class="form-label">هاتف متحرك <span class="text-danger">*</span></label>
                        <input type="text" id="tel1" name="tel1" class="form-control" value="<?php echo htmlspecialchars($client['tel1']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tel2" class="form-label">هاتف آخر</label>
                        <input type="text" id="tel2" name="tel2" class="form-control" value="<?php echo htmlspecialchars($client['tel2']); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($client['email']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fax" class="form-label">فاكس</label>
                        <input type="text" id="fax" name="fax" class="form-control" value="<?php echo htmlspecialchars($client['fax']); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">العنوان</label>
                    <textarea id="address" name="address" class="form-control" rows="2"><?php echo htmlspecialchars($client['address']); ?></textarea>
                </div>

                <div class="text-end mt-4">
                    <a href="clients.php" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
