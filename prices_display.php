<?php
require_once __DIR__ . '/bootstrap.php';

// Permissions Check
$can_view_prices = $row_permcheck['view_prices'] ?? 0;
$can_edit_prices = $row_permcheck['edit_prices'] ?? 0;
$can_delete_prices = $row_permcheck['delete_prices'] ?? 0;

if (!$can_view_prices) {
    header("Location: index.php?err=5");
    exit;
}

// Handle Price Deletion
if (isset($_GET['delid']) && $can_delete_prices) {
    $delid = (int)$_GET['delid'];
    $stmt = $conn->prepare("DELETE FROM prices WHERE id = ?");
    $stmt->bind_param("i", $delid);
    if ($stmt->execute()) {
        header("Location: prices_display.php?msg=1");
        exit;
    } else {
        header("Location: prices_display.php?msg=2");
        exit;
    }
}

// Fetch Prices
$prices = [];
$stmt = $conn->prepare("SELECT * FROM prices ORDER BY price_order ASC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $prices[] = $row;
}

$pageTitle = __('prices_and_services');
include_once 'layout/header.php';
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bx bx-dollar-circle"></i> <?php echo __('prices_and_services'); ?></h3>
            <?php if ($can_edit_prices): ?>
                <a href="prices_add.php" class="btn btn-success">
                    <i class="bx bx-plus"></i> <?php echo __('add_new_service'); ?>
                </a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="pricesTable">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo __('service_name'); ?></th>
                            <th><?php echo __('service_price'); ?></th>
                            <th><?php echo __('service_order'); ?></th>
                            <?php if ($can_edit_prices || $can_delete_prices): ?>
                                <th class="text-center"><?php echo __('actions'); ?></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($prices)): ?>
                            <tr>
                                <td colspan="<?php echo ($can_edit_prices || $can_delete_prices) ? '4' : '3'; ?>" class="text-center"><?php echo __('no_services_found'); ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($prices as $price): ?>
                                <tr>
                                    <td><?php echo safe_output($price['price_name']); ?></td>
                                    <td><?php echo number_format($price['price'], 2); ?></td>
                                    <td><?php echo $price['price_order']; ?></td>
                                    <?php if ($can_edit_prices || $can_delete_prices): ?>
                                        <td class="text-center">
                                            <?php if ($can_edit_prices): ?>
                                                <a href="prices_edit.php?id=<?php echo $price['id']; ?>" class="btn btn-primary btn-sm">
                                                    <i class="bx bx-edit"></i> <?php echo __('edit'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($can_delete_prices): ?>
                                                <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $price['id']; ?>)" class="btn btn-danger btn-sm">
                                                    <i class="bx bx-trash"></i> <?php echo __('delete'); ?>
                                                </a>
                                            <?php endif; ?>
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

<?php if ($can_delete_prices): ?>
<script>
function confirmDelete(priceId) {
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
            window.location.href = 'prices_display.php?delid=' + priceId;
        }
    });
}
</script>
<?php endif; ?>

<?php include_once 'layout/footer.php'; ?>
