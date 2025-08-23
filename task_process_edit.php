<?php
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';

// Ensure user has permission to edit tasks
if ($row_permcheck['admjobs_eperm'] != 1) {
    header('Location: Tasks.php?error=noperms');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    // --- Data Collection and Sanitization ---
    $task_id = (int)$_POST['task_id'];
    $employee_id = (int)$_POST['employee_id'];
    $task_type = (int)$_POST['task_type'];
    $details = trim($_POST['details']);
    $duedate = !empty($_POST['duedate']) ? trim($_POST['duedate']) : null;
    $priority = (int)$_POST['priority'];
    $task_status = (int)$_POST['task_status'];
    $note = trim($_POST['note']);
    $current_user_id = $_SESSION['id'];

    // --- Prepare the main update statement ---
    $stmt_update = $conn->prepare(
        "UPDATE tasks SET 
            employee_id = ?, 
            task_type = ?, 
            details = ?, 
            duedate = ?, 
            priority = ?, 
            task_status = ? 
        WHERE id = ?"
    );
    
    if ($stmt_update) {
        $stmt_update->bind_param("iissiii", $employee_id, $task_type, $details, $duedate, $priority, $task_status, $task_id);
        
        if ($stmt_update->execute()) {
            // --- If a note was added, insert it into the task_notes table ---
            if (!empty($note)) {
                $stmt_note = $conn->prepare("INSERT INTO task_notes (taskid, note, user_id) VALUES (?, ?, ?)");
                if($stmt_note) {
                    $stmt_note->bind_param("isi", $task_id, $note, $current_user_id);
                    $stmt_note->execute();
                    $stmt_note->close();
                }
            }

            // --- Create a detailed log entry, replicating old logic securely ---
            $log_details = "قام بتحديث المهمة رقم " . $task_id;
            // You can add more details to the log here if needed, for example:
            // $log_details .= " وتغيير الحالة إلى " . ($task_status == 2 ? 'منتهية' : 'جاري العمل');
            
            $stmt_log = $conn->prepare("INSERT INTO logs (user_id, details) VALUES (?, ?)");
            if($stmt_log) {
                $stmt_log->bind_param("is", $current_user_id, $log_details);
                $stmt_log->execute();
                $stmt_log->close();
            }

            // Success
            header('Location: Tasks.php?status=updated');
        } else {
            // Handle DB error
            header('Location: EditTask.php?id=' . $task_id . '&error=dberror');
        }
        $stmt_update->close();
    } else {
        // Handle prepare statement error
        header('Location: EditTask.php?id=' . $task_id . '&error=preparefailed');
    }
    
    $conn->close();
    exit();

} else {
    // Redirect if the form was not submitted correctly
    header('Location: Tasks.php');
    exit();
}
