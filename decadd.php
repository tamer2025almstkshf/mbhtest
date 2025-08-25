<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/login_check.php';
require_once __DIR__ . '/permissions_check.php';
require_once __DIR__ . '/golden_pass.php';

// Use FILTER_INPUT for safe and clean input retrieval.
$id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

// Check for the specific permission.
if (isset($row_permcheck['discounts_aperm']) && $row_permcheck['discounts_aperm'] == 1) {
    
    // Sanitize all inputs.
    $date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dec_amount = filter_input(INPUT_POST, 'dec_amount', FILTER_SANITIZE_NUMBER_INT);
    $total_salary = filter_input(INPUT_POST, 'total_salary', FILTER_SANITIZE_NUMBER_INT);
    $reason = filter_input(INPUT_POST, "reason", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Insert the deduction record using our new secure method.
    Database::execute(
        "INSERT INTO incdec (user_id, date, total_salary, amount, reason) VALUES (?, ?, ?, ?, ?)",
        [$id, $date, $total_salary, $dec_amount, $reason],
        "isiis"
    );

    // --- Notification Logic ---
    $respid = $_SESSION['id'];
    $empid = $id;

    if ($empid) {
        // Get the ID of the last inserted record for this user.
        $last_incdec = Database::select("SELECT id FROM incdec WHERE user_id = ? ORDER BY id DESC LIMIT 1", [$empid], "i");
        $target_id = $last_incdec[0]['id'] ?? 0;

        // Get the name of the person who made the deduction.
        $resp_user = Database::select("SELECT name FROM user WHERE id = ?", [$respid], "i");
        $respname = $resp_user[0]['name'] ?? 'System';

        // Prepare notification details.
        $target = "incdec /-/ $target_id";
        $notification = "تم خصم مبلغ بقيمة $dec_amount درهم من راتبك لهذا الشهر من قبل $respname";
        $notification_date = date("Y-m-d");
        $status = 0;
        $timestamp = date("Y-m-d H:i:s");

        // Insert the notification.
        Database::execute(
            "INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$respid, $empid, $target, $target_id, $notification, $notification_date, $status, $timestamp],
            "iisissis"
        );
    }
}

// Redirect back to the employee's time management page.
header("Location: mbhEmps.php?empid=$id&empsection=time-management&time-section=discounts");
exit();
