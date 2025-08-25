<?php
// FILE: BankAccs.php

/**
 * This page handles the listing, adding, and editing of bank accounts.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
require_once __DIR__ . '/bootstrap.php';
include_once 'permissions_check.php'; // Still needed for now
use App\I18n;

I18n::load('translations/BankAccs.yaml');

// 2. PERMISSIONS CHECK
// =============================================================================
$can_view_accounts = ($row_permcheck['accbankaccs_rperm'] === '1');
$can_add_accounts = ($row_permcheck['accbankaccs_aperm'] === '1');
$can_edit_accounts = ($row_permcheck['accbankaccs_eperm'] === '1');
$can_delete_accounts = ($row_permcheck['accbankaccs_dperm'] === '1');

if (!$can_view_accounts) {
    die(I18n::get('access_denied_message'));
}

// 3. SECURE DATA RETRIEVAL FOR MODALS
// =============================================================================
$edit_mode = false;
$edit_data = null;
$attachment_mode = false;
$attachment_data = null;

$edit_id = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
$attach_id = filter_input(INPUT_GET, 'attachments', FILTER_VALIDATE_INT);

if ($edit_id && $can_edit_accounts) {
    $edit_mode = true;
    $stmt_edit = $conn->prepare("SELECT * FROM bank_accounts WHERE id = ?");
    $stmt_edit->bind_param("i", $edit_id);
    $stmt_edit->execute();
    $edit_data = $stmt_edit->get_result()->fetch_assoc();
    $stmt_edit->close();
    if (!$edit_data) { // IDOR Check
        header("Location: BankAccs.php?error=not_found");
        exit();
    }
}

if ($attach_id) {
    $attachment_mode = true;
    $stmt_attach = $conn->prepare("SELECT id, receipt_photo FROM bank_accounts WHERE id = ?");
    $stmt_attach->bind_param("i", $attach_id);
    $stmt_attach->execute();
    $attachment_data = $stmt_attach->get_result()->fetch_assoc();
    $stmt_attach->close();
}

// 4. RENDER PAGE
// =============================================================================
$currentLocale = I18n::getLocale();
?>
<!DOCTYPE html>
<html dir="<?php echo ($currentLocale === 'ar') ? 'rtl' : 'ltr'; ?>" lang="<?php echo $currentLocale; ?>">
<head>
    <title><?php echo I18n::get('bank_accounts'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body style="overflow: auto">
    <div class="container">
        <?php include_once 'sidebar.php'; ?>
        <div class="website">
            <?php include_once 'header.php'; ?>
            <div class="web-page">
                <div class="table-container">
                    <div class="table-header">
                        <h3><?php echo I18n::get('bank_accounts'); ?></h3>
                        <?php if ($can_add_accounts): ?>
                            <button onclick="location.href='BankAccs.php?add=1'"><?php echo I18n::get('add_new_account'); ?></button>
                        <?php endif; ?>
                    </div>

                    <!-- Modal for Add/Edit Form -->
                    <?php if ((isset($_GET['add']) && $can_add_accounts) || ($edit_mode && $edit_data)): ?>
                    <div class="modal-overlay" style="display: block;">
                        <div class="modal-content">
                            <div class="addc-header">
                                <h4><?php echo $edit_mode ? I18n::get('edit_bank_account') : I18n::get('add_new_bank_account'); ?></h4>
                                <a href="BankAccs.php" class="close-button">&times;</a>
                            </div>
                            <form action="<?php echo $edit_mode ? 'editbankacc.php' : 'bankacc.php'; ?>" method="post" enctype="multipart/form-data">
                                <?php if ($edit_mode): ?>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_data['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php endif; ?>
                                <div class="addc-body">
                                    <p><?php echo I18n::get('bank_name'); ?> <font color="red">*</font></p>
                                    <input class="form-input" name="name" value="<?php echo htmlspecialchars($edit_data['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" type="text" required>
                                    <p><?php echo I18n::get('account_number'); ?> <font color="red">*</font></p>
                                    <input class="form-input" name="account_no" value="<?php echo htmlspecialchars($edit_data['account_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" type="text" required>
                                    <p><?php echo I18n::get('notes'); ?></p>
                                    <textarea class="form-input" name="notes" rows="2"><?php echo htmlspecialchars($edit_data['notes'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                                <div class="addc-footer">
                                    <button type="submit" class="form-btn submit-btn"><?php echo I18n::get('save'); ?></button>
                                    <a href="BankAccs.php" class="form-btn cancel-btn"><?php echo I18n::get('cancel'); ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Attachments Modal -->
                    <?php if ($attachment_mode && $attachment_data): ?>
                    <div class="modal-overlay" style="display: block;">
                        <div class="modal-content">
                             <div class="addc-header">
                                <h4 style="margin: auto"><?php echo I18n::get('account_attachments'); ?></h4>
                                <a href="BankAccs.php" class="close-button">&times;</a>
                            </div>
                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                <?php if (!empty($attachment_data['receipt_photo'])): ?>
                                    <div class="attachment-row">
                                        <p><?php echo I18n::get('receipt'); ?>:</p>
                                        <a href="<?php echo htmlspecialchars($attachment_data['receipt_photo'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                                            <?php echo htmlspecialchars(basename($attachment_data['receipt_photo']), ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                        <?php if ($can_delete_accounts): ?>
                                            <a href="baattachdel.php?id=<?php echo $attachment_data['id']; ?>&del=receipt_photo" onclick="return confirm('<?php echo I18n::get('confirm_delete'); ?>');">[<?php echo I18n::get('delete'); ?>]</a>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <p><?php echo I18n::get('no_attachments_found'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Bank Accounts Table -->
                    <div class="table-body">
                        <form action="delbankaccs.php" method="post" onsubmit="return confirm('<?php echo I18n::get('confirm_delete_selected_accounts'); ?>');">
                            <table class="info-table" style="width: 100%;">
                                <thead>
                                    <tr class="infotable-header">
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th><?php echo I18n::get('bank_name'); ?></th>
                                        <th><?php echo I18n::get('account_number'); ?></th>
                                        <th><?php echo I18n::get('balance'); ?></th>
                                        <th><?php echo I18n::get('actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $result = $conn->query("SELECT * FROM bank_accounts ORDER BY id DESC");
                                        while ($row = $result->fetch_assoc()):
                                    ?>
                                    <tr class="infotable-body">
                                        <td><input type="checkbox" name="CheckedD[]" class="user-checkbox" value="<?php echo $row['id']; ?>"></td>
                                        <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row['account_no'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row['account_amount'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <?php if ($can_edit_accounts): ?>
                                                <a href="BankAccs.php?edit=<?php echo $row['id']; ?>"><?php echo I18n::get('edit'); ?></a> |
                                            <?php endif; ?>
                                            <a href="BankAccs.php?attachments=<?php echo $row['id']; ?>"><?php echo I18n::get('attachments'); ?></a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <?php if ($can_delete_accounts): ?>
                                <input type="submit" value="<?php echo I18n::get('delete_selected'); ?>" class="delete-selected">
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/checkAll.js"></script>
    <script src="js/popups.js"></script>
</body>
</html>