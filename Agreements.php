<?php
// FILE: Agreements.php

/**
 * This page handles the listing, adding, and editing of agreements.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
require_once __DIR__ . '/bootstrap.php';
include_once 'permissions_check.php'; // Still need this for now

// 2. PERMISSIONS CHECK
// =============================================================================
$can_view_agreements = ($row_permcheck['agr_rperm'] === '1');
$can_add_agreements = ($row_permcheck['agr_aperm'] === '1');
$can_edit_agreements = ($row_permcheck['agr_eperm'] === '1');
$can_delete_agreements = ($row_permcheck['agr_dperm'] === '1');

if (!$can_view_agreements) {
    die(__('no_permission_to_view'));
}

// 3. DETERMINE MODE (ADD/EDIT) & FETCH DATA
// =============================================================================
$edit_mode = false;
$edit_data = null;
$edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($can_edit_agreements && $edit_id) {
    $edit_mode = true;
    $stmt_edit = $conn->prepare("SELECT * FROM consultations WHERE id = ? AND type = 'agreement'");
    $stmt_edit->bind_param("i", $edit_id);
    $stmt_edit->execute();
    $edit_data = $stmt_edit->get_result()->fetch_assoc();
    $stmt_edit->close();

    if (!$edit_data) {
        header("Location: Agreements.php?error=not_found");
        exit();
    }
}

// 4. RENDER PAGE
// =============================================================================
use App\I18n;
$currentLocale = I18n::getLocale();
?>
<!DOCTYPE html>
<html dir="<?php echo ($currentLocale === 'ar') ? 'rtl' : 'ltr'; ?>" lang="<?php echo $currentLocale; ?>">
<head>
    <title><?php echo __('agreements'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body style="overflow: auto">
    <div class="container">
        <?php include_once 'sidebar.php'; ?>
        <div class="website">
            <?php include_once 'header.php'; ?>
            <div class="web-page">
                <div class="table-container">
                    <div class="table-header">
                        <h3><?php echo __('agreements'); ?></h3>
                        <?php if ($can_add_agreements): ?>
                            <button onclick="location.href='Agreements.php?add=1'"><?php echo __('add_new_agreement'); ?></button>
                        <?php endif; ?>
                    </div>

                    <!-- Modal for Add/Edit -->
                    <?php if (($can_add_agreements && isset($_GET['add'])) || ($edit_mode && $edit_data)): ?>
                    <div class="modal-overlay" style="display: block;">
                        <div class="modal-content">
                            <div class="addc-header">
                                <h4><?php echo $edit_mode ? __('edit_agreement_data') : __('new_agreement'); ?></h4>
                                <a href="Agreements.php" class="close-button">&times;</a>
                            </div>
                            <form action="<?php echo $edit_mode ? 'agredit.php' : 'agradd.php'; ?>" method="post">
                                <?php if ($edit_mode): ?>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_data['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php endif; ?>
                                <div class="addc-body">
                                    <p><?php echo __('client_name'); ?><font color="red">*</font></p>
                                    <input class="form-input" name="client_name" value="<?php echo htmlspecialchars($edit_data['client_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" type="text" required>
                                    
                                    <p><?php echo __('phone'); ?></p>
                                    <input class="form-input" name="telno" value="<?php echo htmlspecialchars($edit_data['telno'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" type="text">
                                    
                                    <!-- Add other fields here, using htmlspecialchars -->
                                </div>
                                <div class="addc-footer">
                                    <button type="submit" class="form-btn submit-btn"><?php echo __('save'); ?></button>
                                    <a href="Agreements.php" class="form-btn cancel-btn"><?php echo __('cancel'); ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Table of Agreements -->
                    <div class="table-body">
                        <table class="info-table" style="width: 100%;">
                            <thead>
                                <tr class="infotable-header">
                                    <th><?php echo __('client_name'); ?></th>
                                    <th><?php echo __('phone'); ?></th>
                                    <th><?php echo __('attendees'); ?></th>
                                    <th><?php echo __('signing_date'); ?></th>
                                    <th><?php echo __('entered_by'); ?></th>
                                    <th><?php echo __('actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $list_sql = "
                                    SELECT 
                                        cons.*,
                                        creator.name as creator_name,
                                        attendee1.name as attendee1_name,
                                        attendee2.name as attendee2_name,
                                        attendee3.name as attendee3_name
                                    FROM consultations cons
                                    LEFT JOIN user creator ON cons.empid = creator.id
                                    LEFT JOIN user attendee1 ON cons.others1 = attendee1.id
                                    LEFT JOIN user attendee2 ON cons.others2 = attendee2.id
                                    LEFT JOIN user attendee3 ON cons.others3 = attendee3.id
                                    WHERE cons.type = 'agreement' ORDER BY cons.id DESC
                                ";
                                $list_result = $conn->query($list_sql);
                                while ($row = $list_result->fetch_assoc()):
                                    $attendees = array_filter([$row['attendee1_name'], $row['attendee2_name'], $row['attendee3_name']]);
                                ?>
                                <tr class="infotable-body">
                                    <td><?php echo htmlspecialchars($row['client_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['telno'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars(implode(', ', $attendees), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['sign_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['creator_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <?php if ($can_edit_agreements): ?>
                                            <a href="Agreements.php?id=<?php echo $row['id']; ?>"><?php echo __('edit'); ?></a>
                                        <?php endif; ?>
                                        <?php if ($can_delete_agreements): ?>
                                            <a href="deleteagreement.php?id=<?php echo $row['id']; ?>" onclick="return confirm('<?php echo __('delete_agreement_confirmation'); ?>');"><?php echo __('delete'); ?></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>