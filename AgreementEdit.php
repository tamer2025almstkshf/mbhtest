<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    // --- Input Validation and Security Checks ---
    $agreement_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$agreement_id) {
        header("Location: Agreements.php?error=invalid_id");
        exit();
    }
    
    // --- Securely fetch permissions ---
    $user_id = $_SESSION['id'];
    $stmt_perm = $conn->prepare("SELECT agr_eperm, agr_dperm FROM user WHERE id = ?");
    $stmt_perm->bind_param("i", $user_id);
    $stmt_perm->execute();
    $perm_row = $stmt_perm->get_result()->fetch_assoc();
    $stmt_perm->close();

    if (empty($perm_row['agr_eperm'])) {
        die("You do not have permission to edit agreements.");
    }

    // --- Securely fetch the specific agreement to be edited ---
    $stmt_edit = $conn->prepare("SELECT * FROM consultations WHERE id = ? AND type = 'agreement'");
    $stmt_edit->bind_param("i", $agreement_id);
    $stmt_edit->execute();
    $agreement_data = $stmt_edit->get_result()->fetch_assoc();
    $stmt_edit->close();

    // IDOR check: Ensure the agreement exists before trying to edit it.
    if (!$agreement_data) {
        header("Location: Agreements.php?error=not_found");
        exit();
    }
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>تعديل اتفاقية</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div class="container">
    <?php include_once 'sidebar.php'; ?>
    <div class="l_data">
        <h3>تعديل اتفاقية</h3>
        
        <!-- Edit Form -->
        <form action="agredit.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($agreement_data['id'], ENT_QUOTES, 'UTF-8'); ?>">
            <table class="table" width="100%">
                <tr>
                    <th align="left">اسم الموكل:</th>
                    <td align="right"><input type="text" name="client_name" value="<?php echo htmlspecialchars($agreement_data['client_name'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                </tr>
                <tr>
                    <th align="left">الجنسية:</th>
                    <td align="right">
                        <!-- It is highly recommended to populate this from a database table -->
                        <select name="nationality">
                            <option value=""></option>
                            <option value="الإمارات العربية المتحدة" <?php if ($agreement_data['nationality'] == 'الإمارات العربية المتحدة') echo 'selected'; ?>>الإمارات العربية المتحدة</option>
                            <!-- ... other countries ... -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <th align="left">الهاتف:</th>
                    <td align="right"><input type="text" name="telno" value="<?php echo htmlspecialchars($agreement_data['telno'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                </tr>
                 <tr>
                    <th align="left">تاريخ توقيع الاتفاقية:</th>
                    <td align="right"><input type="date" name="sign_date" value="<?php echo htmlspecialchars($agreement_data['sign_date'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                </tr>
                <!-- Add other form fields here, ensuring all values are escaped with htmlspecialchars -->
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="تعديل البيانات" class="button">
                    </td>
                </tr>
            </table>
        </form>

        <hr>

        <!-- List of all agreements -->
        <h4>قائمة الاتفاقيات</h4>
        <table class="table" width="100%">
            <thead>
                <tr class="header_table">
                    <th>اسم الموكل</th>
                    <th>الهاتف</th>
                    <th>الحضور</th>
                    <th>تاريخ التوقيع</th>
                    <th>أدخل بواسطة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Optimized query with LEFT JOINs to prevent N+1 problem
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
                        WHERE cons.type = 'agreement'
                        ORDER BY cons.id DESC
                    ";
                    $list_result = $conn->query($list_sql); // This query is safe as it doesn't use user input
                    while($row = $list_result->fetch_assoc()):
                        $attendees = array_filter([$row['attendee1_name'], $row['attendee2_name'], $row['attendee3_name']]);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['client_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['telno'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars(implode(', ', $attendees), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['sign_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['creator_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="AgreementEdit.php?id=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>">تعديل</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>