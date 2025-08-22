<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    $id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    if($row_permcheck['discounts_aperm'] == 1){
        $date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dec_amount = filter_input(INPUT_POST, 'dec_amount', FILTER_SANITIZE_NUMBER_INT);
        $total_salary = filter_input(INPUT_POST, 'total_salary', FILTER_SANITIZE_NUMBER_INT);
        $reason = filter_input(INPUT_POST, "reason", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $respid = $_SESSION['id'];
        $empid = $id;
        
        $stmtn = $conn->prepare("SELECT * FROM incdec WHERE user_id=? ORDER BY id DESC");
        $stmtn->bind_param("i", $empid);
        $stmtn->execute();
        $resultn = $stmtn->get_result();
        $rown = $resultn->fetch_assoc();
        $stmtn->close();
        
        $stmtnr = $conn->prepare("SELECT * FROM user WHERE id=?");
        $stmtnr->bind_param("i", $respid);
        $stmtnr->execute();
        $resultnr = $stmtnr->get_result();
        $rownr = $resultnr->fetch_assoc();
        $stmtnr->close();
        $respname = $rownr['name'];
        
        $target_id = $rown['id'];
        $target = "incdec /-/ $target_id";
        $notification = "تم خصم مبلغ بقيمة $dec_amount درهم من راتبك لهذا الشهر من قبل $respname";
        $notification_date = date("Y-m-d");
        $status = 0;
        $timestamp = date("Y-m-d H:i:s");
        
        if($empid != 0 && $empid !== ''){
            $stmt = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisissis", $respid, $empid, $target, $target_id, $notification, $notification_date, $status, $timestamp);
            $stmt->execute();
            $stmt->close();
        }
        
        $stmt = $conn->prepare("INSERT INTO incdec (user_id, date, total_salary, amount, reason) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isiis", $id, $date, $total_salary, $dec_amount, $reason);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: mbhEmps.php?empid=$id&empsection=time-management&time-section=discounts");
    exit();
?>