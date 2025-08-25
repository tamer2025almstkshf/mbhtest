<?php
// FILE: Lawyers.php

/**
 * This page displays a list of external lawyers or law offices.
 * It allows users with appropriate permissions to view, add, edit,
 * and delete lawyer records.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
require_once __DIR__ . '/bootstrap.php';
$pageTitle = __('law_offices');
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'layout/header.php';

// 2. PERMISSIONS CHECK
// =============================================================================
$can_view = $row_permcheck['emp_perms_view'] === '1';
$can_add = $row_permcheck['emp_perms_add'] === '1';
$can_edit = $row_permcheck['emp_perms_edit'] === '1';
$can_delete = $row_permcheck['emp_perms_delete'] === '1';

if (!$can_view && !$can_add && !$can_edit && !$can_delete) {
    echo '<div class="container mt-5"><div class="alert alert-danger">' . __('no_permission_to_view') . '</div></div>';
    include_once 'layout/footer.php';
    exit();
}

// 3. DATA FETCHING
// =============================================================================
$lawyers = [];
$stmt = $conn->prepare("SELECT id, name, tel_no, about FROM lawyers WHERE name != '' AND tel_no != '' ORDER BY id DESC");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $lawyers[] = $row;
    }
    $stmt->close();
} else {
    error_log("Failed to prepare statement in Lawyers.php: " . $conn->error);
}

// 4. RENDER PAGE
// =============================================================================
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bxs-institution"></i> <?php echo __('law_offices'); ?></h3>
            <?php if ($can_add) : ?>
                <a href="lawyerAdd.php" class="btn btn-primary"><i class="bx bx-plus"></i> <?php echo __('add_new_office'); ?></a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo __('lawyer_name'); ?></th>
                            <th><?php echo __('mobile_landline'); ?></th>
                            <th><?php echo __('summary'); ?></th>
                            <th class="text-center"><?php echo __('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lawyers)): ?>
                            <tr>
                                <td colspan="4" class="text-center"><?php echo __('no_law_offices_registered'); ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lawyers as $lawyer): ?>
                                <tr>
                                    <td><?php echo safe_output($lawyer['name']); ?></td>
                                    <td><?php echo safe_output($lawyer['tel_no']); ?></td>
                                    <td><?php echo safe_output($lawyer['about']); ?></td>
                                    <td class="text-center">
                                        <a href="lawyerAttachments.php?id=<?php echo $lawyer['id']; ?>" class="btn btn-sm btn-secondary" title="<?php echo __('attachments'); ?>"><i class="bx bx-paperclip"></i></a>
                                        
                                        <?php if ($can_edit) : ?>
                                            <a href="lawyerEdit.php?id=<?php echo $lawyer['id']; ?>" class="btn btn-sm btn-info" title="<?php echo __('edit'); ?>"><i class="bx bx-edit"></i></a>
                                        <?php endif; ?>
                                        
                                        <?php if ($can_delete) : ?>
                                            <a href="deletelawyer.php?id=<?php echo $lawyer['id']; ?>" class="btn btn-sm btn-danger" title="<?php echo __('delete'); ?>" onclick="return confirm('<?php echo __('confirm_delete_record'); ?>');"><i class="bx bx-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
