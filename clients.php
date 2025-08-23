<?php
$pageTitle = 'العملاء';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'AES256.php';
include_once 'layout/header.php'; // Use modern header

if ($row_permcheck['clients_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// Determine the client type filter
$type = $_GET['type'] ?? 'all';
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bxs-user-detail"></i> العملاء</h3>
            <div class="d-flex align-items-center">
                <form action="clients.php" method="GET" class="d-flex me-2">
                    <select class="form-select" name="type" onchange="this.form.submit()">
                        <option value="all" <?php if ($type === 'all') echo 'selected'; ?>>الجميع</option>
                        <option value="clients" <?php if ($type === 'clients') echo 'selected'; ?>>الموكلين</option>
                        <option value="opponents" <?php if ($type === 'opponents') echo 'selected'; ?>>الخصوم</option>
                        <option value="subs" <?php if ($type === 'subs') echo 'selected'; ?>>عناوين هامة</option>
                    </select>
                </form>
                <?php if ($row_permcheck['clients_aperm'] == 1) : ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">
                        <i class="bx bx-plus"></i> إضافة جديد
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="clientsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>الكود</th>
                            <th>الإسم</th>
                            <th>الفئة</th>
                            <th>التصنيف</th>
                            <th>بيانات الاتصال</th>
                            <th>عدد القضايا</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php // Table body will be populated by JavaScript ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addClientModalLabel">إضافة عميل جديد</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addClientForm">
            <!-- Form content from clientAdd.php -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-primary" id="saveClientBtn">حفظ</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editClientModalLabel">تعديل بيانات العميل</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editClientForm">
            <!-- Form content will be loaded here by JavaScript -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-primary" id="updateClientBtn">حفظ التغييرات</button>
      </div>
    </div>
  </div>
</div>


<?php include_once 'layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addClientModal = new bootstrap.Modal(document.getElementById('addClientModal'));
    const editClientModal = new bootstrap.Modal(document.getElementById('editClientModal'));
    const addClientForm = document.getElementById('addClientForm');
    const editClientForm = document.getElementById('editClientForm');

    // Function to load clients into the table
    function loadClients() {
        // You would typically fetch this data from an API
        // For now, we will rely on the server-side rendering and just reload the page
        location.reload();
    }

    // Load the Add form content
    fetch('clientAdd.php')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const formContent = doc.querySelector('form');
            if(formContent) {
                addClientForm.innerHTML = formContent.innerHTML;
            }
        });

    // Handle Add Client form submission
    document.getElementById('saveClientBtn').addEventListener('click', function() {
        const formData = new FormData(addClientForm);
        fetch('api/client_add.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                addClientModal.hide();
                loadClients(); 
            } else {
                alert('Error: ' + data.message);
            }
        });
    });

    // Handle opening the Edit modal
    document.getElementById('clientsTable').addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-btn')) {
            const clientId = e.target.getAttribute('data-id');
            
            // Fetch the edit form content and client data
            fetch(`clientEdit.php?id=${clientId}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const formContent = doc.querySelector('form');
                    if(formContent) {
                        editClientForm.innerHTML = formContent.innerHTML;
                        editClientModal.show();
                    }
                });
        }
    });

    // Handle Edit Client form submission
    document.getElementById('updateClientBtn').addEventListener('click', function() {
        const formData = new FormData(editClientForm);
        fetch('api/client_edit.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                editClientModal.hide();
                loadClients();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
    
    // Initial load of clients (using server-side rendering for now)
    // The PHP code above the table will render the initial list
});
</script>
