<?php
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'src/I18n.php';
I18n::load('translations/calls.yaml');

$pageTitle = I18n::get('add_new_call');
include_once 'layout/header.php';

if ($row_permcheck['call_aperm'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">' . I18n::get('no_permission_for_operation') . '</div></div>';
    include_once 'layout/footer.php';
    exit();
}

$branch = $_GET['branch'] ?? '';
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3><i class="bx bx-phone-plus"></i> <?php echo I18n::get('add_new_call'); ?></h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><?php echo I18n::get('home'); ?></a></li>
                    <li class="breadcrumb-item"><a href="clientsCalls.php"><?php echo I18n::get('call_log'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo I18n::get('add_new'); ?></li>
                </ol>
            </nav>
        </div>
        <div class="card-body">
            <form action="call_process_add.php" method="post">
                <input type="hidden" name="branch" value="<?php echo htmlspecialchars($branch); ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="caller_name" class="form-label"><?php echo I18n::get('caller_name'); ?> <span class="text-danger">*</span></label>
                        <input type="text" id="caller_name" name="caller_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="caller_no" class="form-label"><?php echo I18n::get('caller_number'); ?> <span class="text-danger">*</span></label>
                        <input type="text" id="caller_no" name="caller_no" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="details" class="form-label"><?php echo I18n::get('call_details'); ?></label>
                    <textarea id="details" name="details" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="action" class="form-label"><?php echo I18n::get('action_taken'); ?></label>
                    <textarea id="action" name="action" class="form-control" rows="2"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="moved_to" class="form-label"><?php echo I18n::get('call_forwarded_to'); ?></label>
                    <select id="moved_to" name="moved_to" class="form-select">
                        <option value=""><?php echo I18n::get('select_employee'); ?></option>
                        <?php
                        $user_query = "SELECT id, name FROM user WHERE signin_perm = 1 ORDER BY name ASC";
                        $user_result = $conn->query($user_query);
                        while ($user = $user_result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($user['id']) . '">' . htmlspecialchars($user['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="text-end mt-4">
                    <a href="clientsCalls.php" class="btn btn-secondary"><?php echo I18n::get('cancel'); ?></a>
                    <button type="submit" class="btn btn-primary"><?php echo I18n::get('save_call'); ?></button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
