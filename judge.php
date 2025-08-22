<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    $fid = filter_input(INPUT_POST, 'session_fid', FILTER_SANITIZE_NUMBER_INT);
    
    $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
    $stmtid->bind_param("i", $fid);
    $stmtid->execute();
    $resultid = $stmtid->get_result();
    $row_details = $resultid->fetch_assoc();
    $stmtid->close();
    if($admin != 1){
        if($row_details['secret_folder'] == 1){
            $empids = $row_details['secret_emps'];
            $empids = array_filter(array_map('trim', explode(',', $empids)));
            if (!in_array($_SESSION['id'], $empids)) {
                exit();
            }
        }
    }
    if(isset($_REQUEST['booked_final']) && $_REQUEST['booked_final'] !== ''){
        if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){
            $sid = filter_input(INPUT_POST, 'session_id', FILTER_SANITIZE_NUMBER_INT);
            $booked_final = filter_input(INPUT_POST, "booked_final", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $stmtr = $conn->prepare("SELECT * FROM session WHERE session_id=?");
            $stmtr->bind_param("i", $sid);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            $oldbf = $rowr['session_trial'];
            $fid = $rowr['session_fid'];
            
            $flag = '0';
            $action = "تم التعديل على بيانات جلسة :<br>رقم الملف : $fid<br>";
            
            if(isset($oldbf) && $oldbf !== ''){
                if(isset($booked_final) && $booked_final !== $oldbf){
                    $flag = '1';
                    
                    $action = $action."<br>تم تغيير منطوق الحكم : من $oldbf الى $booked_final";
                }
            } else{
                $flag = '1';
                
                $action = $action."<br>منطوق الحكم : $booked_final";
            }
            
            $continue = filter_input(INPUT_POST, 'continue', FILTER_SANITIZE_NUMBER_INT);
            if($continue === '1'){
                $stmt = $conn->prepare("UPDATE session SET session_trial=? WHERE session_id=?");
                $stmt->bind_param("si", $booked_final, $sid);
                $stmt->execute();
            } else{
                $zero = '0';
                $empty = '';
                $stmt = $conn->prepare("UPDATE session SET resume_appeal=?, resume_overdue=?, resume_daysno=?, session_trial=? WHERE session_id=?");
                $stmt->bind_param("isisi", $zero, $empty, $zero, $booked_final, $sid);
                $stmt->execute();
            }
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
            }
        }
        header("Location: Judgement.php?sid=$sid");
        exit();
    } else{
        $sid = filter_input(INPUT_POST, 'session_id', FILTER_SANITIZE_NUMBER_INT);
        
        header("Location: Judgement.php?sid=$sid&error=1");
        exit();
    }
?>