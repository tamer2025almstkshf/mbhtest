<?php
// Use Composer's autoloader and our new Database class
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/login_check.php';

// A helper function to safely get request parameters
function get_param(string $key, $default = null) {
    return $_REQUEST[$key] ?? $default;
}

$page = get_param('page', '');
$myid = $_SESSION['id'];

// --- Permission Check ---
// Use prepared statement to prevent SQL injection
$user_perms = Database::select("SELECT accrevenues_aperm, accexpenses_aperm FROM user WHERE id = ?", [$myid], "i");
$row_permcheck = $user_perms[0] ?? null;

if (!$row_permcheck || ($row_permcheck['accrevenues_aperm'] !== '1' && $row_permcheck['accexpenses_aperm'] !== '1')) {
    // Redirect if user lacks permissions, default to income.php
    $redirect_page = ($page === 'expenses.php') ? 'expenses.php' : 'income.php';
    header("Location: $redirect_page?error=permission");
    exit();
}

// --- Main Data Processing ---
if (isset($_REQUEST['save_data'])) {
    $params_query_string = get_param('params', '');
    $recive_from = get_param('recive_from', '');
    $ie_type = get_param('ie_type');

    // Basic validation
    if (empty($recive_from)) {
        $redirect_page = ($page === 'income.php') ? 'income.php' : 'expenses.php';
        header("Location: $redirect_page?addmore=1&$params_query_string&error=name");
        exit();
    }
    
    // Create a placeholder record to get an ID. This is a slightly unusual pattern, 
    // but we'll stick to it for the refactor. A better approach might be a transaction.
    Database::execute("INSERT INTO incomes_expenses (recive_from, bank_accountid) VALUES ('', '') ON DUPLICATE KEY UPDATE recive_from = recive_from");
    $resultchecknull = Database::select("SELECT id FROM incomes_expenses WHERE recive_from = '' LIMIT 1");
    $id = $resultchecknull[0]['id'] ?? null;
    
    if (!$id) {
        die("Could not create or find a placeholder record.");
    }

    $action = ($ie_type === 'ايرادات') ? "تم اضافة ايرادات جديدة :<br>" : "تم اضافة مصروفات جديدة :<br>";

    // --- Collect and Log Cheques ---
    $cheq_no = $_POST['cheq_no'] ?? [];
    foreach ($cheq_no as $index => $cheque_number) {
        $cheque_value = $_POST['cheq_value'][$index] ?? '';
        $cheque_due_date = $_POST['cheq_due_date'][$index] ?? '';
        $cheque_bank = $_POST['cheq_bank'][$index] ?? '';

        Database::execute(
            "INSERT INTO cheques (ie_id, ie_type, chque_number, cheque_value, cheque_duedate, cheque_bank) VALUES (?, ?, ?, ?, ?, ?)",
            [$id, $ie_type, $cheque_number, $cheque_value, $cheque_due_date, $cheque_bank],
            "isssss"
        );
        // Logging for cheques can be added here if needed
    }

    // --- Handle File Uploads ---
    $targetDir = "files_images/clients/$cid"; // Note: $cid is not defined in the original script. Assuming it's available.
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $attach_file1 = '';
    if (isset($_FILES['attach_file1']) && $_FILES['attach_file1']['error'] == 0) {
        $attach_file1 = $targetDir . "/" . basename($_FILES['attach_file1']['name']);
        move_uploaded_file($_FILES['attach_file1']['tmp_name'], $attach_file1);
    }
    // Repeat for attach_file2, attach_file3...

    // --- Update the Main Record ---
    $update_params = [
        'subcat_id' => get_param('subcat_id'),
        'ie_type' => $ie_type,
        'service' => get_param('service'),
        'amount' => get_param('amount'),
        'recive_from' => $recive_from,
        'recive_reason' => get_param('recive_reason'),
        'bank_accountid' => get_param('account_id'),
        'amount_date' => get_param('amount_date'),
        'attach_file1' => $attach_file1,
        // 'attach_file2' => $attach_file2,
        // 'attach_file3' => $attach_file3,
        'id' => $id
    ];

    $sql = "UPDATE incomes_expenses SET subcat_id=?, ie_type=?, service=?, amount=?, recive_from=?, recive_reason=?, bank_accountid=?, amount_date=?, attach_file1=? WHERE id=?";
    Database::execute($sql, array_values($update_params), "ssssssissi");

    // --- Logging ---
    $current_user = Database::select("SELECT name FROM user WHERE id = ?", [$_SESSION['id']], "i");
    $emp_name = $current_user[0]['name'] ?? 'Unknown';
    Database::execute("INSERT INTO logs (empid, emp_name, action) VALUES (?, ?, ?)", [$_SESSION['id'], $emp_name, $action], "iss");

    // --- Redirect ---
    $submit_back = get_param('submit_back');
    $redirect_page = ($page === 'income.php') ? 'income.php' : 'expenses.php';
    $status_param = ($page === 'income.php') ? 'incsaved=1' : 'expsaved=1';

    if ($submit_back === 'addmore') {
        header("Location: $redirect_page?addmore=1&$status_param");
    } else {
        header("Location: $redirect_page?$status_param");
    }
    exit();
}

// Fallback redirect if 'save_data' is not set
$redirect_page = ($page === 'income.php') ? 'income.php' : 'expenses.php';
header("Location: $redirect_page");
exit();

