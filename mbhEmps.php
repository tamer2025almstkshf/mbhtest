<?php
// Core Includes & Setup
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'AES256.php';

// Use our new SalaryHelper class
use App\Helpers\SalaryHelper;

$pageTitle = 'شؤون الموظفين';
include_once 'layout/header.php'; // Modern header - THIS HANDLES THE CONNECTION

// You should have a central sidebar include if it's on many pages
// include_once 'sidebar.php'; 
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h3><i class='bx bx-user'></i> المستخدمين</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">المستخدمين</li>
                </ol>
            </nav>
        </div>
        <div class="card-body">
            
            <?php
            $myid = $_SESSION['id'];
            $stmtme = $conn->prepare("SELECT * FROM user WHERE id=?");
            $stmtme->bind_param("i", $myid);
            $stmtme->execute();
            $resultme = $stmtme->get_result();
            $rowme = $resultme->fetch_assoc();
            $stmtme->close();

            // ==================================================================
            // Main View: List all employees
            // ==================================================================
            if (!isset($_GET['empadd']) && !isset($_GET['empid']) && !isset($_GET['edit'])) {
                if ($row_permcheck['emp_perms_read'] == 1) {
            ?>
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (!isset($_GET['section']) || $_GET['section'] === 'users') ? 'active' : ''; ?>" href="mbhEmps.php?section=users">المستخدمين</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_GET['section'] === 'permissions') ? 'active' : ''; ?>" href="mbhEmps.php?section=permissions">الصلاحيات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_GET['section'] === 'archived') ? 'active' : ''; ?>" href="mbhEmps.php?section=archived">الموقوفين</a>
                    </li>
                </ul>

                <div class="d-flex justify-content-between mb-3">
                    <?php if ($row_permcheck['emp_perms_add'] == 1) { ?>
                        <a href="employeeAdd.php" class="btn btn-primary"><i class="bx bx-plus"></i> اضافة مستخدم</a>
                    <?php } ?>
                    <input type="text" id="SearchBox" class="form-control w-50" placeholder="البحث...">
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                                <th>المهنة</th>
                                <th>تاريخ اول يوم عمل</th>
                                <th>القسم</th>
                                <th>تاريخ اخر دخول</th>
                                <th>الراتب الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody id="table1">
                            <?php
                            $section = $_GET['section'] ?? 'users';
                            if ($section === 'users' || $section === 'permissions') {
                                $stmt = $conn->prepare("SELECT * FROM user WHERE signin_perm=1");
                            } else if ($section === 'archived') {
                                $stmt = $conn->prepare("SELECT * FROM user WHERE signin_perm=0");
                            } else {
                                $stmt = $conn->prepare("SELECT * FROM user");
                            }
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <tr style="cursor: pointer;" onclick="location.href='mbhEmps.php?empid=<?php echo safe_output($row['id']); ?>&empsection=data-management';">
                                        <td><?php echo safe_output($row['name']); ?></td>
                                        <td><?php echo safe_output($row['id']); ?></td>
                                        <td>
                                            <?php
                                            $job_title_id = $row['job_title'];
                                            $stmtjt = $conn->prepare("SELECT position_name FROM positions WHERE id=?");
                                            $stmtjt->bind_param("i", $job_title_id);
                                            $stmtjt->execute();
                                            $resultjt = $stmtjt->get_result();
                                            if ($rowjt = $resultjt->fetch_assoc()) {
                                                echo safe_output($rowjt['position_name']);
                                            }
                                            $stmtjt->close();
                                            ?>
                                        </td>
                                        <td><?php echo safe_output($row['app_date']); ?></td>
                                        <td><?php echo safe_output($row['section']); ?></td>
                                        <td><?php echo safe_output($row['lastlogin']); ?></td>
                                        <td>
                                            <?php
                                            // Using the new SalaryHelper class
                                            $totalSalary = SalaryHelper::calculateTotalSalary($row);
                                            echo number_format($totalSalary) . ' AED';
                                            ?>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
                } // end perms check
            
            // ==================================================================
            // View Employee Profile
            // ==================================================================
            } else if (isset($_GET['empid']) && !isset($_GET['edit'])) {
                $empid = (int)$_GET['empid'];
                $stmtus = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmtus->bind_param("i", $empid);
                $stmtus->execute();
                $resultus = $stmtus->get_result();
                $rowus = $resultus->fetch_assoc();
                $stmtus->close();
            ?>
                <h4><?php echo safe_output($rowus['name']); ?></h4>
                
                <!-- Refactored Profile sections would go here -->
                <div class="card mt-3">
                    <div class="card-header">المعلومات الوظيفية</div>
                    <div class="card-body">
                        <p><strong>الراتب الشهري:</strong> 
                        <?php 
                            // Using the new helper again
                            $total_salary = SalaryHelper::calculateTotalSalary($rowus);
                            echo number_format($total_salary) . ' AED';
                        ?>
                        </p>
                        <!-- Add other profile details here -->
                    </div>
                </div>

                <a href="mbhEmps.php" class="btn btn-secondary mt-3">العودة إلى القائمة</a>

            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; // Modern footer ?>
