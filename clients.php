<?php
$pageTitle = 'العملاء';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'AES256.php';
include_once 'layout/header.php'; // Use modern header

if ($row_permcheck['clients_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// Determine the client type filter
$type = $_GET['type'] ?? 'all';
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bxs-user-detail"></i> العملاء</h3>
            <div class="d-flex align-items-center">
                <form action="clients.php" method="GET" class="d-flex me-2">
                    <select class="form-select" name="type" onchange="this.form.submit()">
                        <option value="all" <?php if ($type === 'all') echo 'selected'; ?>>الجميع</option>
                        <option value="clients" <?php if ($type === 'clients') echo 'selected'; ?>>الموكلين</option>
                        <option value="opponents" <?php if ($type === 'opponents') echo 'selected'; ?>>الخصوم</option>
                        <option value="subs" <?php if ($type === 'subs') echo 'selected'; ?>>عناوين هامة</option>
                    </select>
                </form>
                <?php if ($row_permcheck['clients_aperm'] == 1) : ?>
                    <a href="clientAdd.php" class="btn btn-primary"><i class="bx bx-plus"></i> إضافة جديد</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>الكود</th>
                            <th>الإسم</th>
                            <th>الفئة</th>
                            <th>التصنيف</th>
                            <th>بيانات الاتصال</th>
                            <th>عدد القضايا</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, arname, engname, client_type, client_kind, tel1, email FROM client WHERE client_kind != '' AND arname != '' AND password != '' AND terror != '1'";
                        $params = [];
                        $types = '';

                        if ($type === 'clients') {
                            $sql .= " AND client_kind='موكل'";
                        } elseif ($type === 'opponents') {
                            $sql .= " AND client_kind='خصم'";
                        } elseif ($type === 'subs') {
                            $sql .= " AND client_kind='عناوين هامة'";
                        }
                        
                        $sql .= " ORDER BY id DESC";
                        
                        $stmt = $conn->prepare($sql);
                        if (!empty($params)) {
                            $stmt->bind_param($types, ...$params);
                        }
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $kind_class = '';
                                if ($row['client_kind'] === 'موكل') $kind_class = 'text-success';
                                if ($row['client_kind'] === 'خصم') $kind_class = 'text-danger';
                        ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo safe_output($row['arname']); ?></td>
                                    <td><?php echo safe_output($row['client_type']); ?></td>
                                    <td class="<?php echo $kind_class; ?>"><?php echo safe_output($row['client_kind']); ?></td>
                                    <td><?php echo safe_output($row['tel1']); ?><br><small><?php echo safe_output($row['email']); ?></small></td>
                                    <td>
                                        <?php
                                        // This count can be optimized in a real-world scenario
                                        $count_stmt = $conn->prepare("SELECT COUNT(file_id) as count FROM file WHERE file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?");
                                        $count_stmt->bind_param("iiiii", $row['id'], $row['id'], $row['id'], $row['id'], $row['id']);
                                        $count_stmt->execute();
                                        $count_result = $count_stmt->get_result();
                                        echo $count_result->fetch_assoc()['count'];
                                        $count_stmt->close();
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($row_permcheck['clients_eperm'] == 1) : ?>
                                            <a href="clientEdit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="تعديل"><i class="bx bx-edit"></i></a>
                                        <?php endif; ?>
                                        <a href="clientAttachments.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-secondary" title="المرفقات"><i class="bx bx-paperclip"></i></a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">لا يوجد عملاء.</td></tr>';
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
