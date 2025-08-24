<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';

    // --- 1. Permission Check ---
    if (empty($perm_row['accbankaccs_rperm'])) {
        die("Access Denied: You do not have permission to view this page.");
    }
    
    // --- 2. Secure Data Retrieval for Edit/Attachments Modals ---
    $edit_mode = false;
    $edit_data = null;
    $attachment_mode = false;
    $attachment_data = null;
    
    $edit_id = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
    $attach_id = filter_input(INPUT_GET, 'attachments', FILTER_VALIDATE_INT);

    if ($edit_id && !empty($perm_row['accbankaccs_eperm'])) {
        $edit_mode = true;
        $stmt_edit = $conn->prepare("SELECT * FROM bank_accounts WHERE id = ?");
        $stmt_edit->bind_param("i", $edit_id);
        $stmt_edit->execute();
        $edit_data = $stmt_edit->get_result()->fetch_assoc();
        $stmt_edit->close();
        if (!$edit_data) { // IDOR Check
            header("Location: BankAccs.php?error=not_found");
            exit();
        }
    }

    if ($attach_id) {
        $attachment_mode = true;
        $stmt_attach = $conn->prepare("SELECT id, receipt_photo FROM bank_accounts WHERE id = ?");
        $stmt_attach->bind_param("i", $attach_id);
        $stmt_attach->execute();
        $attachment_data = $stmt_attach->get_result()->fetch_assoc();
        $stmt_attach->close();
    }
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <title>حسابات البنوك</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body style="overflow: auto">
    <div class="container">
        <?php include_once 'sidebar.php'; ?>
        <div class="website">
            <?php include_once 'header.php'; ?>
            <div class="web-page">
                <div class="table-container">
                    <div class="table-header">
                        <h3>حسابات البنوك</h3>
                        <?php if (!empty($perm_row['accbankaccs_aperm'])): ?>
                            <button onclick="location.href='BankAccs.php?add=1'">إضافة حساب جديد</button>
                        <?php endif; ?>
                    </div>

                    <!-- Modal for Add/Edit Form -->
                    <?php if ((isset($_GET['add']) && !empty($perm_row['accbankaccs_aperm'])) || ($edit_mode && $edit_data)): ?>
                    <div class="modal-overlay" style="display: block;">
                        <div class="modal-content">
                            <div class="addc-header">
                                <h4><?php echo $edit_mode ? "تعديل حساب بنك" : "إضافة حساب بنك جديد"; ?></h4>
                                <a href="BankAccs.php" class="close-button">&times;</a>
                            </div>
                            <form action="<?php echo $edit_mode ? 'editbankacc.php' : 'bankacc.php'; ?>" method="post" enctype="multipart/form-data">
                                <?php if ($edit_mode): ?>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_data['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php endif; ?>
                                <div class="addc-body">
                                    <p>اسم البنك <font color="red">*</font></p>
                                    <input class="form-input" name="name" value="<?php echo htmlspecialchars($edit_data['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" type="text" required>
                                    <p>رقم الحساب <font color="red">*</font></p>
                                    <input class="form-input" name="account_no" value="<?php echo htmlspecialchars($edit_data['account_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" type="text" required>
                                    <!-- Add other fields securely -->
                                    <p>ملاحظات</p>
                                    <textarea class="form-input" name="notes" rows="2"><?php echo htmlspecialchars($edit_data['notes'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                                <div class="addc-footer">
                                    <button type="submit" class="form-btn submit-btn">حفظ</button>
                                    <a href="BankAccs.php" class="form-btn cancel-btn">الغاء</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Attachments Modal -->
                    <?php if ($attachment_mode && $attachment_data): ?>
                    <div class="modal-overlay" style="display: block;">
                        <div class="modal-content">
                             <div class="addc-header">
                                <h4 style="margin: auto">مرفقات الحساب</h4>
                                <a href="BankAccs.php" class="close-button">&times;</a>
                            </div>
                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                <?php if (!empty($attachment_data['receipt_photo'])): ?>
                                    <div class="attachment-row">
                                        <p>الايصال :</p>
                                        <a href="<?php echo htmlspecialchars($attachment_data['receipt_photo'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                                            <?php echo htmlspecialchars(basename($attachment_data['receipt_photo']), ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                        <?php if (!empty($perm_row['accbankaccs_dperm'])): ?>
                                            <a href="baattachdel.php?id=<?php echo $attachment_data['id']; ?>&del=receipt_photo" onclick="return confirm('هل أنت متأكد؟');">[حذف]</a>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <p>لا توجد مرفقات.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Bank Accounts Table -->
                    <div class="table-body">
                        <form action="delbankaccs.php" method="post" onsubmit="return confirm('هل أنت متأكد من حذف الحسابات المحددة؟');">
                            <table class="info-table" style="width: 100%;">
                                <thead>
                                    <tr class="infotable-header">
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>اسم البنك</th>
                                        <th>رقم الحساب</th>
                                        <th>الرصيد</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // This query is safe as it does not use user input.
                                        $result = $conn->query("SELECT * FROM bank_accounts ORDER BY id DESC");
                                        while ($row = $result->fetch_assoc()):
                                    ?>
                                    <tr class="infotable-body">
                                        <td><input type="checkbox" name="CheckedD[]" class="user-checkbox" value="<?php echo $row['id']; ?>"></td>
                                        <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row['account_no'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row['account_amount'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <?php if (!empty($perm_row['accbankaccs_eperm'])): ?>
                                                <a href="BankAccs.php?edit=<?php echo $row['id']; ?>">تعديل</a> |
                                            <?php endif; ?>
                                            <a href="BankAccs.php?attachments=<?php echo $row['id']; ?>">المرفقات</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <?php if (!empty($perm_row['accbankaccs_dperm'])): ?>
                                <input type="submit" value="حذف المحدد" class="delete-selected">
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/checkAll.js"></script>
    <script src="js/popups.js"></script>
</body>
</html>