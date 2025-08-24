<?php
$pageTitle = 'الاستشارات القانونية';
include_once 'bootstrap.php';
include_once 'layout/header.php';

if (!$logged_in) {
    header("Location: login.php");
    exit();
}

if ($row_permcheck['cons_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// Logic to determine the current branch
$current_branch = $_GET['branch'] ?? '';
$user_branch = $row_permcheck['work_place'] ?? '';

// If user is not an admin, force them to their own branch
if ($admin != 1 && $current_branch !== $user_branch) {
    header("Location: consultations.php?branch=$user_branch");
    exit();
}
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bx-support"></i> الاستشارات القانونية</h3>
            <div class="d-flex align-items-center">
                <?php if ($admin == 1) : ?>
                    <form action="consultations.php" method="GET" class="d-flex me-2">
                        <select class="form-select" name="branch" onchange="this.form.submit()">
                            <option value="">كل الفروع</option>
                            <?php
                            $stmt_branchs = $conn->prepare("SELECT * FROM branchs ORDER BY branch ASC");
                            $stmt_branchs->execute();
                            $result_branchs = $stmt_branchs->get_result();
                            while ($row_branch = $result_branchs->fetch_assoc()) {
                                $branch_name = htmlspecialchars($row_branch['branch']);
                                $selected = ($current_branch === $branch_name) ? 'selected' : '';
                                echo "<option value=\"$branch_name\" $selected>$branch_name</option>";
                            }
                            $stmt_branchs->close();
                            ?>
                        </select>
                    </form>
                <?php endif; ?>
                <?php if ($row_permcheck['cons_aperm'] == 1) : ?>
                    <a href="consultationAdd.php?branch=<?php echo urlencode($current_branch); ?>" class="btn btn-primary"><i class="bx bx-plus"></i> إضافة استشارة</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>التاريخ</th>
                            <th>اسم العميل</th>
                            <th>الهاتف</th>
                            <th>البريد الالكتروني</th>
                            <th>الموظف</th>
                            <th>الحالة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $params = [];
                        $types = '';
                        $sql = "SELECT * FROM consultations";

                        if ($admin != 1) {
                            $sql .= " WHERE branch = ?";
                            $params[] = $user_branch;
                            $types .= 's';
                        } elseif (!empty($current_branch)) {
                            $sql .= " WHERE branch = ?";
                            $params[] = $current_branch;
                            $types .= 's';
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
                                $status_class = $row['status'] == 1 ? 'table-success' : '';
                                $status_text = $row['status'] == 1 ? 'عميل حالي' : 'عميل محتمل';
                        ?>
                                <tr class="<?php echo $status_class; ?>">
                                    <td><?php echo safe_output(explode(" ", $row['timestamp'])[0]); ?></td>
                                    <td><?php echo safe_output($row['client_name']); ?></td>
                                    <td><?php echo safe_output($row['telno']); ?></td>
                                    <td><?php echo safe_output($row['email']); ?></td>
                                    <td>
                                        <?php
                                        $emp_id = $row['empid'];
                                        $stmt_emp = $conn->prepare("SELECT name FROM user WHERE id=?");
                                        $stmt_emp->bind_param("i", $emp_id);
                                        $stmt_emp->execute();
                                        $result_emp = $stmt_emp->get_result();
                                        if ($row_emp = $result_emp->fetch_assoc()) {
                                            echo safe_output($row_emp['name']);
                                        }
                                        $stmt_emp->close();
                                        ?>
                                    </td>
                                    <td><span class="badge <?php echo $row['status'] == 1 ? 'bg-success' : 'bg-info'; ?>"><?php echo $status_text; ?></span></td>
                                    <td>
                                        <?php if ($row_permcheck['cons_eperm'] == 1) : ?>
                                            <a href="consultationEdit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="تعديل"><i class="bx bx-edit"></i></a>
                                        <?php endif; ?>
                                        <?php if ($row_permcheck['cons_dperm'] == 1) : ?>
                                            <a href="deleteconsultations.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد؟');"><i class="bx bx-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">لا توجد استشارات.</td></tr>';
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
