<?php
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';

// Check for appropriate permissions
// if ($row_permcheck['petition_eperm'] != 1) {
//     header('Location: index.php?error=noperms');
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['petition_id'])) {
    
    $petition_id = (int)$_POST['petition_id'];
    $fid = (int)$_POST['fid'];
    $petition_date = trim($_POST['petition_date']);
    $petition_type = trim($_POST['petition_type']);
    $petition_decision = trim($_POST['petition_decision']);
    $hearing_lastdate = trim($_POST['hearing_lastdate']);
    $appeal_lastdate = trim($_POST['appeal_lastdate']);
    $current_user_id = $_SESSION['id'];

    // --- Start Transaction ---
    $conn->begin_transaction();

    try {
        // Step 1: Update the petition
        $sql_update = "UPDATE petition SET date=?, type=?, decision=?, hearing_lastdate=?, appeal_lastdate=? WHERE id=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssi", $petition_date, $petition_type, $petition_decision, $hearing_lastdate, $appeal_lastdate, $petition_id);
        $stmt_update->execute();

        // Step 2: Create the log entry
        $log_details = "قام بتحديث بيانات الأمر على عريضة رقم " . $petition_id . " للملف رقم " . $fid;
        $sql_log = "INSERT INTO logs (user_id, details, fid) VALUES (?, ?, ?)";
        $stmt_log = $conn->prepare($sql_log);
        $stmt_log->bind_param("isi", $current_user_id, $log_details, $fid);
        $stmt_log->execute();

        // If both were successful, commit the transaction
        $conn->commit();
        
        // Redirect on success
        header("Location: FileEdit.php?id=" . $fid . "&status=petition_updated");
        exit();

    } catch (mysqli_sql_exception $exception) {
        // If anything failed, roll back the transaction
        $conn->rollback();
        
        // Redirect on failure
        header("Location: petitionEdit.php?id=" . $petition_id . "&error=transaction_failed");
        exit();
    }

} else {
    header('Location: index.php');
    exit();
}
