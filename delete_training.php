<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'secure_filesfunc.php';
    
    if($row_permcheck['trainings_dperm'] == 1){
        if(isset($_GET['trainid'])){
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            $trainid = filter_input(INPUT_GET, 'trainid', FILTER_SANITIZE_NUMBER_INT);
            
            $respid = $_SESSION['id'];
            $empid = $user_id;
            
            $stmtn = $conn->prepare("SELECT * FROM trainings WHERE id=?");
            $stmtn->bind_param("i", $trainid);
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
            $target = "trainings /-/ $target_id";
            $notification = "تم حذف احد دوراتك التدريبية من قبل $respname";
            $notification_date = date("Y-m-d");
            $status = 0;
            $timestamp = date("Y-m-d H:i:s");
            
            if($empid != 0 && $empid !== ''){
                $stmt = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisissis", $respid, $empid, $target, $target_id, $notification, $notification_date, $status, $timestamp);
                $stmt->execute();
                $stmt->close();
            }
            
            $stmt = $conn->prepare("DELETE FROM trainings WHERE id=?");
            $stmt->bind_param("i", $trainid);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: mbhEmps.php?empid=$user_id&empsection=rating-management&rating-section=trainings");
    exit();