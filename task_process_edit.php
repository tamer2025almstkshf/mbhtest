<?php
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';

// Ensure user has permission to edit tasks
if ($row_permcheck['admjobs_eperm'] != 1) {
    // Redirect or show an error message
    header('Location: Tasks.php?error=noperms');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    // Sanitize and validate input
    $task_id = (int)$_POST['task_id'];
    $employee_id = (int)$_POST['employee_id'];
    $task_type = (int)$_POST['task_type'];
    $details = trim($_POST['details']);
    $duedate = !empty($_POST['duedate']) ? trim($_POST['duedate']) : null;
    $priority = (int)$_POST['priority'];

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE tasks SET employee_id = ?, task_type = ?, details = ?, duedate = ?, priority = ? WHERE id = ?");
    
    if ($stmt) {
        $stmt->bind_param("iissii", $employee_id, $task_type, $details, $duedate, $priority, $task_id);
        
        if ($stmt->execute()) {
            // Success
            header('Location: Tasks.php?status=updated');
        } else {
            // Handle error, e.g., redirect with an error message
            header('Location: EditTask.php?id=' . $task_id . '&error=dberror');
        }
        $stmt->close();
    } else {
        // Handle error, e.g., redirect with an error message
        header('Location: EditTask.php?id=' . $task_id . '&error=preparefailed');
    }
    
    $conn->close();
    exit();

} else {
    // Redirect if the form was not submitted correctly
    header('Location: Tasks.php');
    exit();
}
