<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'errorscheck.php';
    
    if($row_permcheck['warnings_dperm'] == 1){
        $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
        $warnid = filter_input(INPUT_GET, 'warnid', FILTER_SANITIZE_NUMBER_INT);
        
        $respid = $_SESSION['id'];
        $empid = $user_id;
        
        $stmtnr = $conn->prepare("SELECT * FROM user WHERE id=?");
        $stmtnr->bind_param("i", $respid);
        $stmtnr->execute();
        $resultnr = $stmtnr->get_result();
        $rownr = $resultnr->fetch_assoc();
        $stmtnr->close();
        $respname = $rownr['name'];
        
        $target_id = $warnid;
        $target = "warnings /-/ $target_id";
        $notification = "تم حذف احد انذاراتك من قبل $respname";
        $notification_date = date("Y-m-d");
        $status = 0;
        $timestamp = date("Y-m-d H:i:s");
        
        if($empid != 0 && $empid !== ''){
            $stmt = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisissis", $respid, $empid, $target, $target_id, $notification, $notification_date, $status, $timestamp);
            $stmt->execute();
            $stmt->close();
        }
        
        $stmt = $conn->prepare("DELETE FROM warnings WHERE id=?");
        $stmt->bind_param("i", $warnid);
        $stmt->execute();
        $stmt->close();
        
        header("Location: mbhEmps.php?empid=$user_id&empsection=rating-management&rating-section=warnings");
        exit;
    }
?>