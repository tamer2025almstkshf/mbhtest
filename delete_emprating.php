<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    $id = filter_input(INPUT_GET, 'rid', FILTER_SANITIZE_NUMBER_INT);
    
    $stmtr = $conn->prepare("SELECT * FROM ratings WHERE id=?");
    $stmtr->bind_param("i", $id);
    $stmtr->execute();
    $resultr = $stmtr->get_result();
    $rowr = $resultr->fetch_assoc();
    $stmtr->close();
    
    $user_id = $rowr['emp_id'];
    
    if($user_id !== $myid && $row_permcheck['rate_dperm'] == 1){
        $olddate = $rowr['rating_date'];
        $oldtype = $rowr['rating_type'];
        $oldattachment = $rowr['attachment'];
        
        $action = "تم حذف تقييم من الموظف صاحب الرقم الوظيفي $user_id<br>";
        $flag = '0';
        
        if(isset($olddate) && $olddate !== ''){
            $flag = '1';
            
            $action = $action."<br>تاريخ التقييم : $olddate";
        }
        if(isset($oldtype) && $oldtype !== ''){
            $flag = '1';
            
            $action = $action."<br>نوع التقييم : $oldtype";
        }
        if(isset($olddate) && $olddate !== ''){
            $flag = '1';
            
            unlink($oldattachment);
            $action = $action."<br>تم حذف المرفق";
        }
        
        if($flag === '1'){
            include_once 'addlog.php';
            
            $respid = $_SESSION['id'];
            
            $stmtn = $conn->prepare("SELECT * FROM ratings WHERE id=?");
            $stmtn->bind_param("i", $id);
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
            $target = "ratings /-/ $target_id";
            $notification = "تم حذف احد تقييماتك من قبل $respname";
            $notification_date = date("Y-m-d");
            $status = 0;
            $timestamp = date("Y-m-d H:i:s");
            
            if($user_id != 0 && $user_id !== ''){
                $stmt = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisissis", $respid, $user_id, $target, $target_id, $notification, $notification_date, $status, $timestamp);
                $stmt->execute();
                $stmt->close();
            }
        }
        
        $stmt = $conn->prepare("DELETE FROM ratings WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: mbhEmps.php?empid=$user_id&empsection=rating-management&rating-section=ratings");
    exit();
?>