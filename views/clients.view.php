<?php
/**
 * View file for displaying the clients list.
 *
 * This file is responsible for the presentation logic of the clients page.
 * It expects the following variables to be passed to it:
 *
 * @var array $clients The list of clients to display.
 * @var array $row_permcheck An array containing the user's permissions.
 * @var string $type The current filter type ('all', 'clients', 'opponents', 'subs').
 */
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bxs-user-detail"></i> العملاء</h3>
            <div class="d-flex align-items-center">
                <form id="filterForm" action="clients.php" method="GET" class="d-flex me-2">
                    <select class="form-select" name="type" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" <?= $type === 'all' ? 'selected' : '' ?>>الجميع</option>
                        <option value="clients" <?= $type === 'clients' ? 'selected' : '' ?>>الموكلين</option>
                        <option value="opponents" <?= $type === 'opponents' ? 'selected' : '' ?>>الخصوم</option>
                        <option value="subs" <?= $type === 'subs' ? 'selected' : '' ?>>عناوين هامة</option>
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
                        <?php if (!empty($clients)) : ?>
                            <?php foreach ($clients as $client) : ?>
                                <tr>
                                    <td><?= $client['id'] ?></td>
                                    <td><?= safe_output($client['arname']) ?></td>
                                    <td><?= safe_output($client['client_type']) ?></td>
                                    <td><?= safe_output($client['client_kind']) ?></td>
                                    <td><?= safe_output($client['tel1']) ?></td>
                                    <td>
                                        <?php if ($row_permcheck['clients_eperm'] == 1) : ?>
                                            <button type="button" class="btn btn-sm btn-info edit-btn" data-id="<?= $client['id'] ?>"><i class="bx bx-edit"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="6" class="text-center">لا يوجد عملاء.</td></tr>
                        <?php endif; ?>
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

<!-- We will move this script to its own file -->
<script src="js/clients-page.js"></script>
