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
                        <label for="job_title" class="form-label">المسمى الوظيفي</label>
                        <div class="input-group">
                            <select id="job_title" name="job_title" class="form-select">
                                <option value="" selected disabled>-- اختر المسمى الوظيفي --</option>
                                <?php
                                $positions_query = "SELECT id, position_name FROM positions ORDER BY position_name ASC";
                                $positions_result = $conn->query($positions_query);
                                if ($positions_result->num_rows > 0) {
                                    while ($pos_row = $positions_result->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($pos_row['id']) . '">' . htmlspecialchars($pos_row['position_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#addPositionModal">+</button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nationality" class="form-label">الجنسية</label>
                        <select id="nationality" name="nationality" class="form-select">
                            <option value="" selected disabled>-- اختر الجنسية --</option>
                            <?php
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
                </div>

                <!-- Other fields remain here... -->
                
                <hr>
                
                <div class="text-end">
                    <button type="submit" name="save_user" class="btn btn-primary">حفظ البيانات</button>
                    <a href="employeeAdd.php" class="btn btn-secondary">إفراغ الحقول</a>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Add Position Modal -->
<div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPositionModalLabel">إضافة مسمى وظيفي جديد</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addPositionForm">
          <div class="mb-3">
            <label for="position_name" class="form-label">اسم المسمى الوظيفي</label>
            <input type="text" class="form-control" id="position_name" name="position_name" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
        <button type="button" class="btn btn-primary" id="savePositionBtn">حفظ</button>
      </div>
    </div>
  </div>
</div>

<?php include_once 'layout/footer.php'; ?>

<script>
document.getElementById('savePositionBtn').addEventListener('click', function() {
    const form = document.getElementById('addPositionForm');
    const formData = new FormData(form);
    const positionName = formData.get('position_name').trim();

    if (!positionName) {
        alert('يرجى إدخال اسم المسمى الوظيفي.');
        return;
    }

    fetch('api/addPosition.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Add the new position to the dropdown
            const jobTitleSelect = document.getElementById('job_title');
            const newOption = new Option(positionName, data.new_position_id, true, true);
            jobTitleSelect.add(newOption);

            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addPositionModal'));
            modal.hide();

            // Clear the input field for next time
            form.reset();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred.');
    });
});
</script>
