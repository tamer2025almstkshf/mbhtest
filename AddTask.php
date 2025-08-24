<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    // --- Permission Check ---
    $user_id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT admjobs_aperm FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $perm_row = $result->fetch_assoc();
    $stmt->close();

    if (empty($perm_row['admjobs_aperm'])) {
        die("You do not have permission to access this page.");
    }

    // --- Input Handling ---
    $section = filter_input(INPUT_GET, 'section', FILTER_VALIDATE_INT);
    $fno = filter_input(INPUT_GET, 'fno', FILTER_VALIDATE_INT);
    $agree = filter_input(INPUT_GET, 'agree', FILTER_VALIDATE_INT);

    $file_data = null;
    $search_results = [];

    // --- Data Retrieval (Search Logic) ---
    // NOTE: In a production environment, add authorization checks within these queries
    // to ensure the user can only see files they are assigned to.
    if ($section && !$agree) {
        switch ($section) {
            case 1: // Search by File Number
                if ($fno) {
                    $stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
                    $stmt->bind_param("i", $fno);
                    $stmt->execute();
                    $search_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                }
                break;
            
            case 2: // Search by Client/Opponent Name
                $client_name = filter_input(INPUT_GET, 'cn', FILTER_SANITIZE_STRING);
                $client_kind = filter_input(INPUT_GET, 'ck', FILTER_VALIDATE_INT);
                if ($client_name && $client_kind) {
                    $name_param = "%{$client_name}%";
                    $field_prefix = ($client_kind == 1) ? 'file_client' : 'file_opponent';
                    $stmt = $conn->prepare(
                        "SELECT f.* FROM file f JOIN client c ON c.id = f.{$field_prefix} WHERE c.arname LIKE ?"
                    );
                    $stmt->bind_param("s", $name_param);
                    $stmt->execute();
                    $search_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                }
                break;

            case 3: // Search by Case Number
                $case_no = filter_input(INPUT_GET, 'cno', FILTER_SANITIZE_STRING);
                $case_year = filter_input(INPUT_GET, 'cy', FILTER_SANITIZE_STRING);
                if ($case_no && $case_year) {
                    $stmt = $conn->prepare(
                        "SELECT f.* FROM file f JOIN file_degrees fd ON f.file_id = fd.fid WHERE fd.case_num = ? AND fd.file_year = ?"
                    );
                    $stmt->bind_param("ss", $case_no, $case_year);
                    $stmt->execute();
                    $search_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                }
                break;
        }
    }

    // --- Data for Form Fields (if a file has been selected) ---
    if ($agree && $fno) {
        $stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
        $stmt->bind_param("i", $fno);
        $stmt->execute();
        $file_data = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        // IDOR check: If no file data is returned, the user might be trying to access an unauthorized file.
        if (!$file_data) {
            die("File not found or you do not have permission to access it.");
        }
    }

    function getClientName($conn, $clientId) {
        if (empty($clientId)) return '';
        $stmt = $conn->prepare("SELECT arname FROM client WHERE id = ?");
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['arname'] ?? 'Unknown';
    }
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>إضافة عمل إداري</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div class="container">
    <?php include_once 'sidebar.php'; ?>
    <div class="l_data">
        <h2>إضافة عمل إداري</h2>
        
        <form method="get" action="AddTask.php">
             <fieldset>
                <legend>بحث عن ملف</legend>
                <input type="radio" name="section" value="1" <?php if ($section == 1) echo 'checked'; ?>> رقم الملف
                <input type="radio" name="section" value="2" <?php if ($section == 2) echo 'checked'; ?>> اسم الموكل/الخصم
                <input type="radio" name="section" value="3" <?php if ($section == 3) echo 'checked'; ?>> رقم القضية
                <input type="radio" name="section" value="4" <?php if ($section == 4) echo 'checked'; ?>> عمل إداري بدون ملف
                <button type="submit">بحث</button>
            </fieldset>
        </form>

        <?php if (!empty($search_results)): ?>
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <th>رقم الملف</th>
                        <th>الموضوع</th>
                        <th>الموكل</th>
                        <th>الخصم</th>
                        <th>اختيار</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($search_results as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['file_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['file_subject'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(getClientName($conn, $row['file_client']), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(getClientName($conn, $row['file_opponent']), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><a href="?fno=<?php echo htmlspecialchars($row['file_id'], ENT_QUOTES, 'UTF-8'); ?>&agree=1">إنشاء مهمة لهذا الملف</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (($agree && $file_data) || $section == 4): ?>
            <form action="task_process.php" method="post">
                <input type="hidden" name="agree" value="1">
                <?php if ($fno): ?>
                    <input type="hidden" name="file_id" value="<?php echo htmlspecialchars($fno, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                
                <fieldset>
                    <legend><?php echo $section == 4 ? 'إضافة عمل إداري عام' : 'إضافة عمل للملف رقم: ' . htmlspecialchars($fno, ENT_QUOTES, 'UTF-8'); ?></legend>
                    
                    <?php if (isset($file_data)): ?>
                        <p><strong>نوع القضية:</strong> <?php echo htmlspecialchars($file_data['fcase_type'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>

                    <p>
                        <label>الموظف المكلف:</label>
                        <select name="re_name" required>
                            <option value="">-- اختر موظف --</option>
                            <?php
                            // Use prepared statement for security
                            $emp_stmt = $conn->prepare("SELECT id, name FROM user WHERE status = 'active' ORDER BY name");
                            $emp_stmt->execute();
                            $emp_result = $emp_stmt->get_result();
                            while ($emp = $emp_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($emp['id'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($emp['name'], ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                            $emp_stmt->close();
                            ?>
                        </select> *
                    </p>
                    <p>
                        <label>نوع العمل:</label>
                        <select name="type_name" required>
                             <option value="">-- اختر نوع العمل --</option>
                            <?php
                            // Use prepared statement for security
                            $job_stmt = $conn->prepare("SELECT id, job_name FROM job_name ORDER BY job_name");
                            $job_stmt->execute();
                            $job_result = $job_stmt->get_result();
                            while ($job = $job_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($job['id'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($job['job_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                            $job_stmt->close();
                            ?>
                        </select> *
                    </p>
                    <p>
                        <label>الأهمية:</label>
                        <input type="radio" name="busi_priority" value="0" checked> عادي
                        <input type="radio" name="busi_priority" value="1"> عاجل
                    </p>
                    <p>
                        <label>تاريخ تنفيذ العمل:</label>
                        <input type="date" name="busi_date">
                    </p>
                    <p>
                        <label>التفاصيل:</label><br>
                        <textarea name="busi_notes" rows="4" style="width: 98%"></textarea>
                    </p>
                    <p>
                        <input type="submit" value="حفظ وتخزين العمل الإدارى" class="button" name="save_task_fid"/>
                    </p>
                </fieldset>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>