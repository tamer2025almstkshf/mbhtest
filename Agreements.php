<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    // --- Securely fetch user permissions ---
    $user_id = $_SESSION['id'];
    $stmt_perm = $conn->prepare("SELECT agr_rperm, agr_aperm, agr_eperm, agr_dperm FROM user WHERE id = ?");
    $stmt_perm->bind_param("i", $user_id);
    $stmt_perm->execute();
    $perm_row = $stmt_perm->get_result()->fetch_assoc();
    $stmt_perm->close();

    if (empty($perm_row['agr_rperm'])) {
        die("You do not have permission to view this page.");
    }

    // --- Determine Mode (Add vs. Edit) and Fetch Data for Edit ---
    $edit_mode = false;
    $edit_data = null;
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!empty($perm_row['agr_eperm']) && $edit_id) {
        $edit_mode = true;
        $stmt_edit = $conn->prepare("SELECT * FROM consultations WHERE id = ? AND type = 'agreement'");
        $stmt_edit->bind_param("i", $edit_id);
        $stmt_edit->execute();
        $edit_data = $stmt_edit->get_result()->fetch_assoc();
        $stmt_edit->close();
        // IDOR Check: if $edit_data is null, the ID was invalid or not an agreement.
        if (!$edit_data) {
            header("Location: Agreements.php?error=not_found");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <title>الاتفاقيات</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body style="overflow: auto">
    <div class="container">
        <?php include_once 'sidebar.php'; ?>
        <div class="website">
            <?php include_once 'header.php'; ?>
            <div class="web-page">
                <div class="table-container">
                    <div class="table-header">
                        <h3>الاتفاقيات</h3>
                        <?php if (!empty($perm_row['agr_aperm'])): ?>
                            <button onclick="location.href='Agreements.php?add=1'">إضافة اتفاقية جديدة</button>
                        <?php endif; ?>
                    </div>

                    <!-- Modal for Add/Edit -->
                    <?php if ((!empty($perm_row['agr_aperm']) && isset($_GET['add'])) || ($edit_mode && $edit_data)): ?>
                    <div class="modal-overlay" style="display: block;">
                        <div class="modal-content">
                            <div class="addc-header">
                                <h4><?php echo $edit_mode ? "تعديل بيانات الاتفاقية" : "اتفاقية جديدة"; ?></h4>
                                <a href="Agreements.php" class="close-button">&times;</a>
                            </div>
                            <form action="<?php echo $edit_mode ? 'agredit.php' : 'agradd.php'; ?>" method="post">
                                <?php if ($edit_mode): ?>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_data['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php endif; ?>
                                <div class="addc-body">
                                    <p>اسم الموكل<font color="red">*</font></p>
                                    <input class="form-input" name="client_name" value="<?php echo htmlspecialchars($edit_data['client_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" type="text" required>
                                    
                                    <p>الهاتف</p>
                                    <input class="form-input" name="telno" value="<?php echo htmlspecialchars($edit_data['telno'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" type="text">
                                    
                                    <!-- Add other fields here, using htmlspecialchars -->
                                </div>
                                <div class="addc-footer">
                                    <button type="submit" class="form-btn submit-btn">حفظ</button>
                                    <a href="Agreements.php" class="form-btn cancel-btn">الغاء</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Table of Agreements -->
                    <div class="table-body">
                        <table class="info-table" style="width: 100%;">
                            <thead>
                                <tr class="infotable-header">
                                    <th>اسم الموكل</th>
                                    <th>الهاتف</th>
                                    <th>الحضور</th>
                                    <th>تاريخ التوقيع</th>
                                    <th>المُدخل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $list_sql = "
                                    SELECT 
                                        cons.*,
                                        creator.name as creator_name,
                                        attendee1.name as attendee1_name,
                                        attendee2.name as attendee2_name,
                                        attendee3.name as attendee3_name
                                    FROM consultations cons
                                    LEFT JOIN user creator ON cons.empid = creator.id
                                    LEFT JOIN user attendee1 ON cons.others1 = attendee1.id
                                    LEFT JOIN user attendee2 ON cons.others2 = attendee2.id
                                    LEFT JOIN user attendee3 ON cons.others3 = attendee3.id
                                    WHERE cons.type = 'agreement' ORDER BY cons.id DESC
                                ";
                                $list_result = $conn->query($list_sql); // This query is safe as it doesn't use user input
                                while ($row = $list_result->fetch_assoc()):
                                    $attendees = array_filter([$row['attendee1_name'], $row['attendee2_name'], $row['attendee3_name']]);
                                ?>
                                <tr class="infotable-body">
                                    <td><?php echo htmlspecialchars($row['client_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['telno'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars(implode(', ', $attendees), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['sign_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['creator_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <?php if (!empty($perm_row['agr_eperm'])): ?>
                                            <a href="Agreements.php?id=<?php echo $row['id']; ?>">تعديل</a>
                                        <?php endif; ?>
                                        <?php if (!empty($perm_row['agr_dperm'])): ?>
                                            <a href="deleteagreement.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this agreement?');">حذف</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>