<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    // --- Securely fetch user permissions ---
    $user_id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT cfiles_rperm, cfiles_eperm, cfiles_dperm FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $perm_row = $result->fetch_assoc();
    $stmt->close();

    if (empty($perm_row['cfiles_rperm'])) {
        die("You do not have permission to view this page.");
    }

    // --- Sanitize all GET parameters ---
    $search_params = [];
    $allowed_params = ['type', 'place', 'class', 'fid', 'subject', 'client', 'ccharacteristic', 'opponent', 'ocharacteristic', 'advisor', 'researcher', 'ctype', 'prosec', 'station', 'court', 'opp', 'cno', 'year', 'jud', 'from', 'to', 'extended', 'judge', 'pending', 'fwork', 'archived'];
    foreach ($allowed_params as $param) {
        $search_params[$param] = filter_input(INPUT_GET, $param, FILTER_SANITIZE_STRING);
    }

    $results = [];
    $query_executed = !empty(array_filter($search_params));

    if ($query_executed) {
        // --- Securely build the dynamic WHERE clause ---
        $conditions = [];
        $params = [];
        $types = '';

        // Simple key-value mappings for the `file` table
        $file_mappings = [
            'type' => 'file_type', 'place' => 'frelated_place', 'class' => 'file_class',
            'fid' => 'file_id', 'subject' => 'file_subject', 'client' => 'file_client',
            'ccharacteristic' => 'fclient_characteristic', 'opponent' => 'file_opponent',
            'ocharacteristic' => 'fopponent_characteristic', 'advisor' => 'flegal_advisor',
            'researcher' => 'flegal_researcher', 'ctype' => 'fcase_type',
            'prosec' => 'file_prosecution', 'station' => 'fpolice_station', 'court' => 'file_court'
        ];

        foreach ($file_mappings as $key => $column) {
            if (!empty($search_params[$key])) {
                $conditions[] = "`$column` = ?";
                $params[] = $search_params[$key];
                $types .= 's';
            }
        }
        
        // Handle file status checkboxes
        $status_conditions = [];
        if (!empty($search_params['pending'])) $status_conditions[] = 'في الانتظار';
        if (!empty($search_params['fwork'])) $status_conditions[] = 'متداول';
        if (!empty($search_params['archived'])) $status_conditions[] = 'مؤرشف';

        if (!empty($status_conditions)) {
            $placeholders = implode(',', array_fill(0, count($status_conditions), '?'));
            $conditions[] = "file_status IN ($placeholders)";
            foreach ($status_conditions as $status) {
                $params[] = $status;
                $types .= 's';
            }
        }

        // The logic for joining with `session` and `file_degrees` is complex
        // and would require subqueries or JOINs. This is a simplified example.
        // For a full implementation, you'd add JOINs to the main query.
        
        $sql = "SELECT * FROM file";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        $sql .= " ORDER BY file_id DESC";

        $stmt = $conn->prepare($sql);
        if ($stmt && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        if($stmt){
            $stmt->execute();
            $result_set = $stmt->get_result();
            $results = $result_set->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }
    }

    /** Helper function to safely pre-fill form fields */
    function e_val($key) {
        global $search_params;
        return isset($search_params[$key]) ? htmlspecialchars($search_params[$key], ENT_QUOTES, 'UTF-8') : '';
    }
    
    /** Helper function to safely check radio/checkboxes */
    function e_check($key, $value) {
        global $search_params;
        return (isset($search_params[$key]) && $search_params[$key] == $value) ? 'checked' : '';
    }
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <title>البحث المتقدم</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <?php include_once 'sidebar.php'; ?>
    <div class="website">
        <?php include_once 'header.php'; ?>
        <div class="web-page">
            <div class="advinputs-container">
                <form name="SearchForm" action="AdvancedSearch.php" method="get">
                    <h2 class="advinputs-h2">البحث المتقدم</h2>
                    
                    <!-- Search Form Inputs -->
                    <div class="advinputs">
                        <div class="input-container">
                            <p>نوع الملف</p>
                            <select name="type">
                                <option value=""></option>
                                <option value="مدني -عمالى" <?php if(e_val('type') == 'مدني -عمالى') echo 'selected'; ?>>مدني -عمالى</option>
                                <!-- Other options here -->
                            </select>
                        </div>
                        <div class="input-container">
                            <p>رقم الملف</p>
                            <input type="number" name="fid" value="<?php echo e_val('fid'); ?>">
                        </div>
                         <!-- Add all other search fields here, using e_val(), e_check() -->
                    </div>

                    <button type="submit" class="green-button">البحث في الملفات</button>
                </form>

                <?php if ($query_executed): ?>
                <div class="table-container">
                    <h3>نتائج البحث (<?php echo count($results); ?>)</h3>
                    <table class="info-table">
                        <thead>
                            <tr>
                                <th>رقم الملف</th>
                                <th>الموضوع</th>
                                <!-- Other headers -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($results)): ?>
                                <tr><td colspan="2">لا توجد نتائج تطابق بحثك.</td></tr>
                            <?php else: ?>
                                <?php foreach ($results as $row): ?>
                                <tr class="infotable-body">
                                    <td><?php echo htmlspecialchars($row['file_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['file_subject'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <!-- Other data columns, all escaped -->
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>