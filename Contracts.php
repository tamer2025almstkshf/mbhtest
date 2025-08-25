<?php
// FILE: Contracts.php

// 1. INCLUDES & BOOTSTRAPPING
require_once __DIR__ . '/bootstrap.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
use App\I18n;

I18n::load('translations/Contracts.yaml');

// 2. PERMISSIONS CHECK
$can_view = $row_permcheck['emp_perms_view'] == 1; 
$can_add = $row_permcheck['emp_perms_add'] == 1;
$can_edit = $row_permcheck['emp_perms_edit'] == 1;
$can_delete = $row_permcheck['emp_perms_delete'] == 1;

if (!$can_view && !$can_add && !$can_edit && !$can_delete) {
    http_response_code(403);
    die(I18n::get('no_permission_to_view'));
}

// 3. DATA FETCHING & INITIALIZATION
$page_mode = 'list'; 
$edit_data = null;
$attachment_data = null;

if (isset($_GET['edit']) && $can_edit) {
    $page_mode = 'edit';
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM contracts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} elseif (isset($_GET['addmore']) && $can_add) {
    $page_mode = 'add';
} elseif (isset($_GET['attachments'])) {
    $page_mode = 'attachments';
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM contracts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $attachment_data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Fetch all contracts for the main list
$contracts = [];
$stmt = $conn->prepare("SELECT * FROM contracts ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $contracts[] = $row;
}
$stmt->close();

$currentLocale = I18n::getLocale();
?>
<!DOCTYPE html>
<html dir="<?php echo ($currentLocale === 'ar') ? 'rtl' : 'ltr'; ?>" lang="<?php echo $currentLocale; ?>">
<head>
    <title><?php echo I18n::get('contracts_and_licenses'); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="css/styles.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/favicon.ico">
</head>
<body style="overflow: auto; padding-bottom: 50px;">
    <div class="container">
        <?php include_once 'sidebar.php'; ?>
        <div class="website">
            <?php include_once 'header.php'; ?>
            <main class="web-page">
                <div class="table-container">
                    <header class="table-header">
                        <h3><?php echo I18n::get('contracts_and_licenses'); ?></h3>
                        <?php if ($can_add): ?>
                            <button class="add-btn" onclick="openModal('addEditModal')"><i class='bx bx-plus'></i> <?php echo I18n::get('add_contract'); ?></button>
                        <?php endif; ?>
                    </header>

                    <div class="table-body">
                        <table class="info-table" id="contractsTable">
                            <thead>
                                <tr>
                                    <th><?php echo I18n::get('owner'); ?></th>
                                    <th><?php echo I18n::get('type'); ?></th>
                                    <th><?php echo I18n::get('time_period'); ?></th>
                                    <th><?php echo I18n::get('branch'); ?></th>
                                    <th><?php echo I18n::get('place'); ?></th>
                                    <th width="100px"><?php echo I18n::get('actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($contracts)): ?>
                                    <tr><td colspan="6"><?php echo I18n::get('no_contracts_to_display'); ?></td></tr>
                                <?php else: ?>
                                    <?php foreach ($contracts as $contract): ?>
                                    <tr>
                                        <td><?php echo safe_output($contract['owner']); ?></td>
                                        <td><?php echo safe_output($contract['rent_lic']); ?></td>
                                        <td>
                                            <?php echo I18n::get('from'); ?>: <?php echo safe_output($contract['starting_d']); ?><br>
                                            <?php echo I18n::get('to'); ?>: <?php echo safe_output($contract['ending_d']); ?>
                                        </td>
                                        <td><?php echo safe_output($contract['branch']); ?></td>
                                        <td><?php echo safe_output($contract['place']); ?></td>
                                        <td class="actions-cell">
                                            <a href="?attachments=1&id=<?php echo $contract['id']; ?>" class="action-btn" title="<?php echo I18n::get('attachments'); ?>"><i class='bx bx-paperclip'></i></a>
                                            <?php if ($can_edit): ?>
                                                <a href="?edit=1&id=<?php echo $contract['id']; ?>" class="action-btn" title="<?php echo I18n::get('edit'); ?>"><i class='bx bx-edit'></i></a>
                                            <?php endif; ?>
                                            <?php if ($can_delete): ?>
                                                <a href="deletecontract.php?id=<?php echo $contract['id']; ?>" class="action-btn delete" title="<?php echo I18n::get('delete'); ?>" onclick="return confirm('<?php echo I18n::get('confirm_delete_contract'); ?>')"><i class='bx bx-trash'></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="addEditModal" class="modal-overlay" style="display: <?php echo ($page_mode === 'add' || $page_mode === 'edit') ? 'block' : 'none'; ?>">
        <div class="modal-content">
            <header class="modal-header">
                <h4><?php echo ($page_mode === 'edit') ? I18n::get('edit_contract_details') : I18n::get('new_contract'); ?></h4>
                <a href="Contracts.php" class="close-button">&times;</a>
            </header>
            <form action="<?php echo ($page_mode === 'edit') ? 'contedit.php' : 'contadd.php'; ?>" method="post" enctype="multipart/form-data">
                <?php if ($page_mode === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo safe_output($edit_data['id']); ?>">
                <?php endif; ?>
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="owner"><?php echo I18n::get('owner'); ?> <span class="required">*</span></label>
                            <input class="form-input" id="owner" name="owner" value="<?php echo safe_output($edit_data['owner'] ?? ''); ?>" type="text" required>
                        </div>
                        <div class="form-group">
                            <label for="rent_lic"><?php echo I18n::get('type'); ?></label>
                            <select class="form-input" id="rent_lic" name="rent_lic">
                                <option value="عقد إيجار" <?php if (($edit_data['rent_lic'] ?? '') === 'عقد إيجار') echo 'selected'; ?>><?php echo I18n::get('lease_contract'); ?></option>
                                <option value="رخصة تجارية" <?php if (($edit_data['rent_lic'] ?? '') === 'رخصة تجارية') echo 'selected'; ?>><?php echo I18n::get('trade_license'); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="starting_d"><?php echo I18n::get('starts_from'); ?></label>
                            <input class="form-input" id="starting_d" name="starting_d" value="<?php echo safe_output($edit_data['starting_d'] ?? ''); ?>" type="date">
                        </div>
                        <div class="form-group">
                            <label for="ending_d"><?php echo I18n::get('ends_on'); ?></label>
                            <input class="form-input" id="ending_d" name="ending_d" value="<?php echo safe_output($edit_data['ending_d'] ?? ''); ?>" type="date">
                        </div>
                        <div class="form-group">
                            <label for="place"><?php echo I18n::get('place'); ?></label>
                            <input class="form-input" id="place" name="place" value="<?php echo safe_output($edit_data['place'] ?? ''); ?>" type="text">
                        </div>
                         <div class="form-group">
                            <label for="branch"><?php echo I18n::get('branch'); ?></label>
                            <select class="form-input" id="branch" name="branch">
                                <option value="الشارقة" <?php if (($edit_data['branch'] ?? '') === 'الشارقة') echo 'selected'; ?>><?php echo I18n::get('sharjah'); ?></option>
                                <option value="دبي" <?php if (($edit_data['branch'] ?? '') === 'دبي') echo 'selected'; ?>><?php echo I18n::get('dubai'); ?></option>
                                <option value="عجمان" <?php if (($edit_data['branch'] ?? '') === 'عجمان') echo 'selected'; ?>><?php echo I18n::get('ajman'); ?></option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label for="notes"><?php echo I18n::get('address_notes'); ?></label>
                            <textarea class="form-input" id="notes" name="notes" rows="3"><?php echo safe_output($edit_data['notes'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group full-width">
                             <label><?php echo I18n::get('attachments'); ?></label>
                             <input type="file" name="cont_lic_pic" class="form-input">
                             <?php if(!empty($edit_data['cont_lic_pic'])) echo '<p>' . I18n::get('current_file') . ': '.basename(safe_output($edit_data['cont_lic_pic'])).'</p>'; ?>
                        </div>

                    </div>
                </div>
                <footer class="modal-footer">
                    <button type="submit" class="form-btn submit-btn"><?php echo I18n::get('save'); ?></button>
                    <a href="Contracts.php" class="form-btn cancel-btn"><?php echo I18n::get('cancel'); ?></a>
                </footer>
            </form>
        </div>
    </div>
    
     <!-- Attachments Viewer Modal -->
    <div id="attachmentsModal" class="modal-overlay" style="display: <?php echo ($page_mode === 'attachments') ? 'block' : 'none'; ?>">
        <div class="modal-content">
             <header class="modal-header">
                <h4><?php echo I18n::get('contract_attachments_for_id'); ?> <?php echo safe_output($attachment_data['id'] ?? ''); ?></h4>
                <a href="Contracts.php" class="close-button">&times;</a>
            </header>
            <div class="modal-body">
                <?php if ($attachment_data): ?>
                    <ul class="attachment-list">
                        <?php for ($i = 0; $i < 5; $i++): 
                            $key = ($i == 0) ? 'cont_lic_pic' : 'attachment' . $i;
                            $label = ($i == 0) ? I18n::get('contract_license_image') : I18n::get('attachment') . ' ' . $i;
                            if (!empty($attachment_data[$key])): ?>
                                <li>
                                    <i class='bx bxs-file'></i>
                                    <strong><?php echo $label; ?>:</strong>
                                    <a href="<?php echo safe_output($attachment_data[$key]); ?>" target="_blank">
                                        <?php echo basename(safe_output($attachment_data[$key])); ?>
                                    </a>
                                    <?php if($can_delete): ?>
                                    <a href="contattachdel.php?id=<?php echo safe_output($attachment_data['id']);?>&del=<?php echo $key; ?>&page=Contracts.php" class="delete-link" onclick="return confirm('<?php echo I18n::get('confirm_delete'); ?>')">
                                        <i class='bx bx-trash'></i>
                                    </a>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </ul>
                <?php else: ?>
                    <p><?php echo I18n::get('no_attachments_to_display'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="js/popups.js"></script>
    <script src="js/tablePages.js"></script>
</body>
</html>
