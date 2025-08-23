<?php
$pageTitle = 'سجل المكالمات الهاتفية';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'AES256.php';
include_once 'layout/header.php'; // Use modern header

if ($row_permcheck['call_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// Logic to determine the current branch
$current_branch = $_GET['branch'] ?? '';
$user_branch = $_SESSION['work_place'] ?? ''; // Assuming work_place is stored in session

// If user is not an admin, force them to their own branch
if ($admin != 1 && $current_branch !== $user_branch) {
    header("Location: clientsCalls.php?branch=$user_branch");
    exit();
}
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bx-phone-call"></i> سجل المكالمات الهاتفية</h3>
            <div class="d-flex align-items-center">
                <?php if ($admin == 1) : ?>
                    <form action="clientsCalls.php" method="GET" class="d-flex me-2">
                        <select class="form-select" name="branch" onchange="this.form.submit()">
                            <option value="">كل الفروع</option>
                            <?php
                            // New, fully data-driven branch selector
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
                <?php if ($row_permcheck['call_aperm'] == 1) : ?>
                    <a href="callAdd.php?branch=<?php echo urlencode($current_branch); ?>" class="btn btn-primary"><i class="bx bx-plus"></i> إضافة مكالمة</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>اسم المتصل</th>
                            <th>رقم المتصل</th>
                            <th>التفاصيل</th>
                            <th>الاجراء المتخذ</th>
                            <th>تم تحويلها إلى</th>
                            <th>أدخلت بواسطة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $params = [];
                        $types = '';
                        $sql = "SELECT * FROM clientsCalls";

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
                        ?>
                                <tr>
                                    <td><?php echo safe_output($row['caller_name']); ?></td>
                                    <td><?php echo safe_output($row['caller_no']); ?></td>
                                    <td><?php echo safe_output($row['details']); ?></td>
                                    <td><?php echo safe_output($row['action']); ?></td>
                                    <td>
                                        <?php
                                        if (!empty($row['moved_to'])) {
                                            $moved_to_id = $row['moved_to'];
                                            $stmt_user = $conn->prepare("SELECT name FROM user WHERE id=?");
                                            $stmt_user->bind_param("i", $moved_to_id);
                                            $stmt_user->execute();
                                            $result_user = $stmt_user->get_result();
                                            if ($row_user = $result_user->fetch_assoc()) {
                                                echo safe_output($row_user['name']);
                                            }
                                            $stmt_user->close();
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if (!empty($row['timestamp'])) {
                                            list($user_id, $date) = explode(" <br> ", $row['timestamp']);
                                            $stmt_creator = $conn->prepare("SELECT name FROM user WHERE id=?");
                                            $stmt_creator->bind_param("i", $user_id);
                                            $stmt_creator->execute();
                                            $result_creator = $stmt_creator->get_result();
                                            if ($row_creator = $result_creator->fetch_assoc()) {
                                                echo safe_output($row_creator['name']);
                                            }
                                            $stmt_creator->close();
                                            echo "<br><small class='text-muted'>$date</small>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($row_permcheck['call_eperm'] == 1) : ?>
                                            <a href="callEdit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="تعديل"><i class="bx bx-edit"></i></a>
                                        <?php endif; ?>
                                        <?php if ($row_permcheck['call_dperm'] == 1) : ?>
                                            <a href="deletecall.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد؟');"><i class="bx bx-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">لا توجد مكالمات مسجلة.</td></tr>';
                        }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
