<?php
/**
 * View file for displaying the clients list.
 */
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bxs-user-detail"></i> <?php echo __('clients'); ?></h3>
            <div class="d-flex align-items-center">
                <form id="filterForm" action="clients.php" method="GET" class="d-flex me-2">
                    <select class="form-select" name="type" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" <?= $type === 'all' ? 'selected' : '' ?>><?php echo __('all'); ?></option>
                        <option value="clients" <?= $type === 'clients' ? 'selected' : '' ?>><?php echo __('clients'); ?></option>
                        <option value="opponents" <?= $type === 'opponents' ? 'selected' : '' ?>><?php echo __('opponents'); ?></option>
                        <option value="subs" <?= $type === 'subs' ? 'selected' : '' ?>><?php echo __('important_addresses'); ?></option>
                    </select>
                </form>
                <?php if ($row_permcheck['clients_aperm'] == 1) : ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#clientModal" onclick="prepareAddModal()">
                        <i class="bx bx-plus"></i> <?php echo __('add_new'); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo __('code'); ?></th>
                            <th><?php echo __('name'); ?></th>
                            <th><?php echo __('category'); ?></th>
                            <th><?php echo __('classification'); ?></th>
                            <th><?php echo __('contact_info'); ?></th>
                            <th><?php echo __('actions'); ?></th>
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
                            <tr><td colspan="6" class="text-center"><?php echo __('no_clients_found'); ?></td></tr>
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo __('cancel'); ?></button>
        <button type="button" class="btn btn-primary" id="saveClientBtn"><?php echo __('save'); ?></button>
      </div>
    </div>
  </div>
</div>

<script src="js/clients-page.js"></script>
