<?php
$pageTitle = 'المصروفات';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'layout/header.php';

if ($row_permcheck['accexpenses_rperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bx-wallet"></i> المصروفات</h3>
            <?php if ($row_permcheck['accexpenses_aperm'] == 1) : ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#expenseModal" onclick="prepareAddModal()">
                    <i class="bx bx-plus"></i> إضافة مصروف جديد
                </button>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>التاريخ</th>
                            <th>المبلغ</th>
                            <th>الفئة</th>
                            <th>الوصف</th>
                            <th>أدخل بواسطة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display expenses
                        $sql = "SELECT e.*, u.name as user_name FROM expenses e JOIN user u ON e.user_id = u.id ORDER BY e.expense_date DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['expense_date']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($row['amount'], 2)); ?> AED</td>
                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                    <td>
                                        <?php if ($row_permcheck['accexpenses_eperm'] == 1) : ?>
                                            <button type="button" class="btn btn-sm btn-info edit-btn" data-id="<?php echo $row['id']; ?>"><i class="bx bx-edit"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="6" class="text-center">لا توجد مصروفات مسجلة.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Expense Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expenseModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="expenseForm">
            <input type="hidden" id="expense_id" name="id">
            <div class="mb-3">
                <label for="date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">الفئة <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-primary" id="saveExpenseBtn">حفظ</button>
      </div>
    </div>
  </div>
</div>

<?php include_once 'layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const expenseModal = new bootstrap.Modal(document.getElementById('expenseModal'));
    const expenseModalLabel = document.getElementById('expenseModalLabel');
    const expenseForm = document.getElementById('expenseForm');
    
    window.prepareAddModal = function() {
        expenseForm.reset();
        document.getElementById('expense_id').value = '';
        expenseModalLabel.textContent = 'إضافة مصروف جديد';
    }

    document.querySelector('.table').addEventListener('click', function (e) {
        const editButton = e.target.closest('.edit-btn');
        if (editButton) {
            const id = editButton.dataset.id;
            expenseModalLabel.textContent = 'تعديل المصروف';
            
            fetch(`api/getExpense.php?id=${id}`)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const data = result.data;
                        document.getElementById('expense_id').value = data.id;
                        document.getElementById('date').value = data.expense_date;
                        document.getElementById('amount').value = data.amount;
                        document.getElementById('category').value = data.category;
                        document.getElementById('description').value = data.description;
                        expenseModal.show();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    });

    document.getElementById('saveExpenseBtn').addEventListener('click', function() {
        const formData = new FormData(expenseForm);
        fetch('api/saveExpense.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                expenseModal.hide();
                location.reload(); // Simple reload to see changes
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
});
</script>
