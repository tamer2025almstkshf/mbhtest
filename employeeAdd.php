<?php
$pageTitle = 'إضافة موظف جديد';
include_once 'connection.php';
include_once 'login_check.php'; // Ensure user is logged in
include_once 'layout/header.php'; // Use the new reusable header

// Check for the required permissions
$myid = $_SESSION['id'];
$query_permcheck = "SELECT emp_perms_add FROM user WHERE id=?";
$stmt_permcheck = $conn->prepare($query_permcheck);
$stmt_permcheck->bind_param("i", $myid);
$stmt_permcheck->execute();
$result_permcheck = $stmt_permcheck->get_result();
$row_permcheck = $result_permcheck->fetch_assoc();

if ($row_permcheck['emp_perms_add'] !== '1') {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لهذه العملية.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
?>

<div class="container mt-4">
    <?php 
    // These includes are for the user info and sidebar, keep them if they are part of your layout
    // include_once 'userInfo.php'; 
    // include_once 'sidebar.php';
    ?>

    <div class="card">
        <div class="card-header">
            <h3>إضافة موظف جديد</h3>
            <p>
                <a href="index.php">الصفحة الرئيسية</a> &raquo;
                <a href="employees.php">شؤون الموظفين</a> &raquo; إضافة موظف
            </p>
        </div>
        <div class="card-body">
            <form action="empadd.php" method="post" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">اسم الدخول <span class="text-danger">*</span></label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">اسم الموظف <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tel1" class="form-label">هاتف متحرك 1 <span class="text-danger">*</span></label>
                        <input type="text" id="tel1" name="tel1" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tel2" class="form-label">هاتف متحرك 2</label>
                        <input type="text" id="tel2" name="tel2" class="form-control">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">البريد الالكتروني <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="nationality" class="form-label">الجنسية</label>
                    <select id="nationality" name="nationality" class="form-select">
                        <option value="" selected disabled>-- اختر الجنسية --</option>
                        <?php
                        // Dynamically populate countries from the database
                        $country_query = "SELECT name FROM countries ORDER BY name ASC";
                        $country_result = $conn->query($country_query);
                        if ($country_result->num_rows > 0) {
                            while ($country_row = $country_result->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($country_row['name']) . '">' . htmlspecialchars($country_row['name']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Add other form fields here, styled with Bootstrap -->
                <!-- Example for الجنس (Gender) -->
                <div class="mb-3">
                    <label class="form-label">الجنس</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sex" id="sex_male" value="ذكر">
                            <label class="form-check-label" for="sex_male">ذكر</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sex" id="sex_female" value="انثى">
                            <label class="form-check-label" for="sex_female">انثى</label>
                        </div>
                    </div>
                </div>

                <!-- You can continue this pattern for all other fields -->
                
                <hr>
                
                <div class="text-end">
                    <button type="submit" name="save_user" class="btn btn-primary">حفظ البيانات</button>
                    <a href="employeeAdd.php" class="btn btn-secondary">إفراغ الحقول</a>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
// We can also refactor the employee list into a separate file later
// For now, it's commented out to focus on the "Add Employee" form.
/*
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>الموظفين العاملين</h3>
        </div>
        <div class="card-body">
            // The employee list table would go here
        </div>
    </div>
</div>
*/
?>

<?php include_once 'layout/footer.php'; // Use the new reusable footer ?>
