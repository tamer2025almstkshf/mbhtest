<?php
require_once __DIR__ . '/bootstrap.php';

// Permissions Check
$can_view_logs = $row_permcheck['view_logs'] ?? 0;
$can_delete_logs = $row_permcheck['delete_logs'] ?? 0;

if (!$can_view_logs) {
    // Redirect to a 'permission denied' page or the dashboard
    header("Location: index.php?err=5");
    exit;
}

// Handle Log Deletion
if (isset($_GET['delid']) && $can_delete_logs) {
    $delid = (int)$_GET['delid'];
    $stmt = $conn->prepare("DELETE FROM logs WHERE id = ?");
    $stmt->bind_param("i", $delid);
    if ($stmt->execute()) {
        header("Location: Logs.php?msg=1");
        exit;
    } else {
        header("Location: Logs.php?msg=2");
        exit;
    }
}

// Fetch Logs
$logs = [];
$stmt = $conn->prepare("SELECT logs.*, user.username FROM logs JOIN user ON logs.user_id = user.id ORDER BY logs.log_date DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

$pageTitle = __('program_logs');
include_once 'layout/header.php';
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bx-history"></i> <?php echo __('program_logs'); ?></h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="logsTable">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo __('employee_name'); ?></th>
                            <th><?php echo __('action'); ?></th>
                            <th><?php echo __('time_and_date'); ?></th>
                            <?php if ($can_delete_logs): ?>
                                <th class="text-center"><?php echo __('actions'); ?></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="<?php echo $can_delete_logs ? '4' : '3'; ?>" class="text-center"><?php echo __('no_logs_found'); ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?php echo safe_output($log['username']); ?></td>
                                    <td><?php echo safe_output($log['log_text']); ?></td>
                                    <td><?php echo date("Y-m-d h:i A", strtotime($log['log_date'])); ?></td>
                                    <?php if ($can_delete_logs): ?>
                                        <td class="text-center">
                                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $log['id']; ?>)" class="btn btn-danger btn-sm">
                                                <i class="bx bx-trash"></i> <?php echo __('delete'); ?>
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if ($can_delete_logs): ?>
<script>
function confirmDelete(logId) {
    Swal.fire({
        title: '<?php echo __('are_you_sure'); ?>',
        text: "<?php echo __('this_action_cannot_be_undone'); ?>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<?php echo __('yes_delete_it'); ?>',
        cancelButtonText: '<?php echo __('cancel'); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'Logs.php?delid=' + logId;
        }
    });
}
</script>
<?php endif; ?>

<?php include_once 'layout/footer.php'; ?>
