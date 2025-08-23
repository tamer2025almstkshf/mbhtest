<?php
$pageTitle = 'العملاء';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'layout/header.php'; // Use modern header

if ($row_permcheck['clients_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
$type = $_GET['type'] ?? 'all';
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bxs-user-detail"></i> العملاء</h3>
            <div class="d-flex align-items-center">
                <form id="filterForm" action="clients.php" method="GET" class="d-flex me-2">
                    <select class="form-select" name="type" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" <?php if ($type === 'all') echo 'selected'; ?>>الجميع</option>
                        <option value="clients" <?php if ($type === 'clients') echo 'selected'; ?>>الموكلين</option>
                        <option value="opponents" <?php if ($type === 'opponents') echo 'selected'; ?>>الخصوم</option>
                        <option value="subs" <?php if ($type === 'subs') echo 'selected'; ?>>عناوين هامة</option>
                    </select>
                </form>
                <?php if ($row_permcheck['clients_aperm'] == 1) : ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#clientModal" onclick="prepareAddModal()">
                        <i class="bx bx-plus"></i> إضافة جديد
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>الكود</th>
                            <th>الإسم</th>
                            <th>الفئة</th>
                            <th>التصنيف</th>
                            <th>بيانات الاتصال</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // PHP code to display the initial list of clients
                        $sql = "SELECT id, arname, client_type, client_kind, tel1, email FROM client WHERE client_kind != '' AND arname != ''";
                        if ($type === 'clients') $sql .= " AND client_kind='موكل'";
                        elseif ($type === 'opponents') $sql .= " AND client_kind='خصم'";
                        elseif ($type === 'subs') $sql .= " AND client_kind='عناوين هامة'";
                        $sql .= " ORDER BY id DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . safe_output($row['arname']) . "</td>";
                                echo "<td>" . safe_output($row['client_type']) . "</td>";
                                echo "<td>" . safe_output($row['client_kind']) . "</td>";
                                echo "<td>" . safe_output($row['tel1']) . "</td>";
                                echo "<td>";
                                if ($row_permcheck['clients_eperm'] == 1) {
                                    echo '<button type="button" class="btn btn-sm btn-info edit-btn" data-id="' . $row['id'] . '"><i class="bx bx-edit"></i></button>';
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo '<tr><td colspan="6" class="text-center">لا يوجد عملاء.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Unified Client Modal -->
<div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="clientModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="clientForm">
          <input type="hidden" id="client_id" name="client_id">
          <!-- Form fields will be dynamically populated here -->
          <div id="clientFormContent"></div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-primary" id="saveClientBtn">حفظ</button>
      </div>
    </div>
  </div>
</div>

<?php include_once 'layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const clientModal = new bootstrap.Modal(document.getElementById('clientModal'));
    const clientModalLabel = document.getElementById('clientModalLabel');
    const clientForm = document.getElementById('clientForm');
    const clientFormContent = document.getElementById('clientFormContent');
    const clientIdField = document.getElementById('client_id');

    // Function to create form fields HTML
    function createFormFields(data = {}) {
        const countriesOptions = `
            <option value="">-- اختر الجنسية --</option>
            <?php
            $country_query = "SELECT name FROM countries ORDER BY name ASC";
            $country_result = $conn->query($country_query);
            while ($country_row = $country_result->fetch_assoc()) {
                echo '<option value=\"' . htmlspecialchars($country_row['name']) . '\">' . htmlspecialchars($country_row['name']) . '</option>';
            }
            ?>
        `;
        
        return `
            <h4>المعلومات الأساسية</h4><hr>
            <div class="row">
                <div class="col-md-4 mb-3"><label for="arname" class="form-label">الإسم (عربي) <span class="text-danger">*</span></label><input type="text" id="arname" name="arname" class="form-control" value="${data.arname || ''}" required></div>
                <div class="col-md-4 mb-3"><label for="engname" class="form-label">الإسم (إنجليزي)</label><input type="text" id="engname" name="engname" class="form-control" value="${data.engname || ''}"></div>
                <div class="col-md-4 mb-3"><label for="client_kind" class="form-label">التصنيف <span class="text-danger">*</span></label><select id="client_kind" name="client_kind" class="form-select" required><option value="موكل" ${data.client_kind === 'موكل' ? 'selected' : ''}>موكل</option><option value="خصم" ${data.client_kind === 'خصم' ? 'selected' : ''}>خصم</option><option value="عناوين هامة" ${data.client_kind === 'عناوين هامة' ? 'selected' : ''}>عناوين هامة</option></select></div>
            </div>
            <div class="row">
                 <div class="col-md-6 mb-3"><label for="client_type" class="form-label">الفئة <span class="text-danger">*</span></label><select id="client_type" name="client_type" class="form-select" required><option value="شخص" ${data.client_type === 'شخص' ? 'selected' : ''}>شخص</option><option value="شركة" ${data.client_type === 'شركة' ? 'selected' : ''}>شركة</option><option value="حكومة / مؤسسات" ${data.client_type === 'حكومة / مؤسسات' ? 'selected' : ''}>حكومة / مؤسسات</option></select></div>
                 <div class="col-md-6 mb-3"><label for="country" class="form-label">الجنسية</label><select id="country" name="country" class="form-select">${countriesOptions}</select></div>
            </div>
            <h4 class="mt-4">معلومات الاتصال</h4><hr>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="tel1" class="form-label">هاتف متحرك <span class="text-danger">*</span></label><input type="text" id="tel1" name="tel1" class="form-control" value="${data.tel1 || ''}" required></div>
                <div class="col-md-6 mb-3"><label for="tel2" class="form-label">هاتف آخر</label><input type="text" id="tel2" name="tel2" class="form-control" value="${data.tel2 || ''}"></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="email" class="form-label">البريد الإلكتروني</label><input type="email" id="email" name="email" class="form-control" value="${data.email || ''}"></div>
                <div class="col-md-6 mb-3"><label for="fax" class="form-label">فاكس</label><input type="text" id="fax" name="fax" class="form-control" value="${data.fax || ''}"></div>
            </div>
            <div class="mb-3"><label for="address" class="form-label">العنوان</label><textarea id="address" name="address" class="form-control" rows="2">${data.address || ''}</textarea></div>
        `;
    }

    window.prepareAddModal = function() {
        clientForm.reset();
        clientIdField.value = '';
        clientModalLabel.textContent = 'إضافة عميل جديد';
        clientFormContent.innerHTML = createFormFields();
    }

    document.querySelector('.table').addEventListener('click', function (e) {
        const editButton = e.target.closest('.edit-btn');
        if (editButton) {
            const id = editButton.dataset.id;
            clientModalLabel.textContent = 'تعديل بيانات العميل';
            clientIdField.value = id;

            fetch(`api/getClient.php?id=${id}`)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        clientFormContent.innerHTML = createFormFields(result.data);
                        document.getElementById('country').value = result.data.country; // Set selected country
                        clientModal.show();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    });

    document.getElementById('saveClientBtn').addEventListener('click', function() {
        const formData = new FormData(clientForm);
        fetch('api/saveClient.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                clientModal.hide();
                location.reload(); // Simple reload to see changes
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
});
</script>
