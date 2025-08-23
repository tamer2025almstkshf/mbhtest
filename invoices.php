<?php
$pageTitle = 'الفواتير';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'layout/header.php';

// Assuming a general finance permission. Adjust if you have a specific one for invoices.
if ($row_permcheck['accfinance_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bx-file"></i> الفواتير</h3>
            <?php // Assuming an 'invoices_aperm' permission for adding
            if ($row_permcheck['invoices_aperm'] == 1) : ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#invoiceModal" onclick="prepareAddModal()">
                    <i class="bx bx-plus"></i> إنشاء فاتورة جديدة
                </button>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>العميل</th>
                            <th>تاريخ الإصدار</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>المبلغ الإجمالي</th>
                            <th>الحالة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display invoices
                        $sql = "SELECT i.*, c.arname as client_name 
                                FROM invoices i 
                                JOIN client c ON i.client_id = c.id 
                                ORDER BY i.issue_date DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $status_badge = 'secondary';
                                if ($row['status'] == 'Paid') $status_badge = 'success';
                                if ($row['status'] == 'Overdue') $status_badge = 'danger';
                                if ($row['status'] == 'Pending') $status_badge = 'warning';
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['issue_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($row['total_amount'], 2)); ?> AED</td>
                                    <td><span class="badge bg-<?php echo $status_badge; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                    <td>
                                        <?php // Assuming an 'invoices_eperm' permission for editing
                                        if ($row_permcheck['invoices_eperm'] == 1) : ?>
                                            <button type="button" class="btn btn-sm btn-info edit-btn" data-id="<?php echo $row['id']; ?>"><i class="bx bx-edit"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">لا توجد فواتير مسجلة.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="invoiceModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="invoiceForm">
            <input type="hidden" id="invoice_id" name="id">
            <div class="mb-3">
                <label for="client_id" class="form-label">العميل <span class="text-danger">*</span></label>
                <select class="form-select" id="client_id" name="client_id" required>
                    <option value="">-- اختر العميل --</option>
                    <?php
                    $client_query = "SELECT id, arname FROM client WHERE client_kind='موكل' ORDER BY arname ASC";
                    $client_result = $conn->query($client_query);
                    while ($client = $client_result->fetch_assoc()) {
                        echo '<option value="' . $client['id'] . '">' . htmlspecialchars($client['arname']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="issue_date" class="form-label">تاريخ الإصدار <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="issue_date" name="issue_date" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="due_date" class="form-label">تاريخ الاستحقاق <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="due_date" name="due_date" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="total_amount" class="form-label">المبلغ الإجمالي <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount" required>
            </div>
             <div class="mb-3">
                <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="Paid">Paid</option>
                    <option value="Overdue">Overdue</option>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-primary" id="saveInvoiceBtn">حفظ</button>
      </div>
    </div>
  </div>
</div>

<?php include_once 'layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
    const invoiceModalLabel = document.getElementById('invoiceModalLabel');
    const invoiceForm = document.getElementById('invoiceForm');
    
    window.prepareAddModal = function() {
        invoiceForm.reset();
        document.getElementById('invoice_id').value = '';
        invoiceModalLabel.textContent = 'إنشاء فاتورة جديدة';
    }

    document.querySelector('.table').addEventListener('click', function (e) {
        const editButton = e.target.closest('.edit-btn');
        if (editButton) {
            const id = editButton.dataset.id;
            invoiceModalLabel.textContent = 'تعديل الفاتورة';
            
            fetch(`api/getInvoice.php?id=${id}`)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const data = result.data;
                        document.getElementById('invoice_id').value = data.id;
                        document.getElementById('client_id').value = data.client_id;
                        document.getElementById('issue_date').value = data.issue_date;
                        document.getElementById('due_date').value = data.due_date;
                        document.getElementById('total_amount').value = data.total_amount;
                        document.getElementById('status').value = data.status;
                        invoiceModal.show();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    });

    document.getElementById('saveInvoiceBtn').addEventListener('click', function() {
        const formData = new FormData(invoiceForm);
        fetch('api/saveInvoice.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                invoiceModal.hide();
                location.reload(); // Simple reload to see changes
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
});
</script>
