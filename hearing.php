<?php
$pageTitle = 'رول الجلسات';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'layout/header.php'; // Use modern header

if ($row_permcheck['sessionrole_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// Get filter parameters
$view_mode = $_GET['view'] ?? 'week'; // 'week' or 'range'
$start_date_str = $_GET['from'] ?? '';
$end_date_str = $_GET['to'] ?? '';
$court_id = isset($_GET['court']) ? (int)$_GET['court'] : 0;
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h3><i class="bx bx-calendar"></i> رول الجلسات</h3>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form action="hearing.php" method="GET" class="mb-4 p-3 bg-light border rounded">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="from" class="form-label">من تاريخ</label>
                        <input type="date" id="from" name="from" class="form-control" value="<?php echo safe_output($start_date_str); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="to" class="form-label">الى تاريخ</label>
                        <input type="date" id="to" name="to" class="form-control" value="<?php echo safe_output($end_date_str); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="court" class="form-label">المحكمة</label>
                        <select id="court" name="court" class="form-select">
                            <option value="0">كل المحاكم</option>
                            <?php
                            $court_query = "SELECT id, court_name FROM court ORDER BY court_name ASC";
                            $court_result = $conn->query($court_query);
                            while ($row_court = $court_result->fetch_assoc()) {
                                $selected = ($court_id == $row_court['id']) ? 'selected' : '';
                                echo '<option value="' . $row_court['id'] . '" ' . $selected . '>' . safe_output($row_court['court_name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="view" value="range" class="btn btn-primary w-100">بحث</button>
                        <a href="hearing.php?view=week" class="btn btn-secondary w-100 mt-2">جلسات هذا الأسبوع</a>
                    </div>
                </div>
            </form>

            <!-- Hearings Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>تاريخ الجلسة</th>
                            <th>القضية</th>
                            <th>الموكل</th>
                            <th>الخصم</th>
                            <th>قرار الجلسة</th>
                            <th>ملاحظات</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Build the query based on the view mode
                        if ($view_mode == 'week' && empty($start_date_str)) {
                            $start_date = new DateTime();
                            $end_date = (new DateTime())->modify('+7 days');
                        } else {
                            $start_date = DateTime::createFromFormat('Y-m-d', $start_date_str);
                            $end_date = DateTime::createFromFormat('Y-m-d', $end_date_str);
                        }

                        if ($start_date && $end_date) {
                            $sql = "SELECT s.*, f.file_court, f.file_client, f.file_opponent 
                                    FROM session s 
                                    JOIN file f ON s.session_fid = f.file_id 
                                    WHERE s.session_date BETWEEN ? AND ?";
                            
                            $params = [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')];
                            $types = "ss";

                            if ($court_id > 0) {
                                // We need to get the court name to match the file_court field
                                $court_name_stmt = $conn->prepare("SELECT court_name FROM court WHERE id = ?");
                                $court_name_stmt->bind_param("i", $court_id);
                                $court_name_stmt->execute();
                                $court_name_result = $court_name_stmt->get_result();
                                if($court_name_row = $court_name_result->fetch_assoc()){
                                    $sql .= " AND f.file_court = ?";
                                    $params[] = $court_name_row['court_name'];
                                    $types .= "s";
                                }
                                $court_name_stmt->close();
                            }
                            $sql .= " ORDER BY s.session_date DESC, s.id DESC";

                            $stmt = $conn->prepare($sql);
                            if ($stmt) {
                                $stmt->bind_param($types, ...$params);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // Fetch client and opponent names in a separate, secure query
                                        $client_name = 'N/A';
                                        if(!empty($row['file_client'])){
                                            $client_stmt = $conn->prepare("SELECT arname FROM client WHERE id = ?");
                                            $client_stmt->bind_param("i", $row['file_client']);
                                            $client_stmt->execute();
                                            $client_res = $client_stmt->get_result();
                                            if($client_row = $client_res->fetch_assoc()){
                                                $client_name = $client_row['arname'];
                                            }
                                            $client_stmt->close();
                                        }

                                        $opponent_name = 'N/A';
                                        if(!empty($row['file_opponent'])){
                                            $opponent_stmt = $conn->prepare("SELECT arname FROM client WHERE id = ?");
                                            $opponent_stmt->bind_param("i", $row['file_opponent']);
                                            $opponent_stmt->execute();
                                            $opponent_res = $opponent_stmt->get_result();
                                            if($opponent_row = $opponent_res->fetch_assoc()){
                                                $opponent_name = $opponent_row['arname'];
                                            }
                                            $opponent_stmt->close();
                                        }
                        ?>
                                    <tr>
                                        <td><?php echo safe_output($row['session_date']); ?></td>
                                        <td>
                                            <a href="CasePreview.php?fid=<?php echo $row['session_fid']; ?>">
                                                <?php echo safe_output($row['case_num'] . '/' . $row['year']); ?>
                                            </a>
                                            <br>
                                            <small class="text-muted"><?php echo safe_output($row['session_degree']); ?></small>
                                        </td>
                                        <td><?php echo safe_output($client_name); ?></td>
                                        <td><?php echo safe_output($opponent_name); ?></td>
                                        <td><?php echo safe_output($row['session_decission']); ?></td>
                                        <td><?php echo safe_output($row['session_note']); ?></td>
                                        <td>
                                            <a href="hearing_edit.php?sid=<?php echo $row['session_id']; ?>&fid=<?php echo $row['session_fid']; ?>" class="btn btn-sm btn-info" title="تعديل"><i class="bx bx-edit"></i></a>
                                        </td>
                                    </tr>
                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center">لا توجد جلسات في هذا النطاق.</td></tr>';
                                }
                                $stmt->close();
                            }
                        } else if ($view_mode == 'range'){
                             echo '<tr><td colspan="7" class="text-center">يرجى تحديد تواريخ صحيحة للبحث.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; // Use modern footer ?>
