<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
    
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
    
    if($row_permcheck['session_eperm'] == 1){
        if((isset($_REQUEST['file_degree_id']) && $_REQUEST['file_degree_id'] !== '')){
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
            
            $flag = '0';
            $action = "تم تعديل احد القضايا المتقابلة :<br>رقم الملف : $fid<br>";
            
            $stmtr = $conn->prepare("SELECT * FROM oppcase WHERE id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            
            $oldfdi = $rowr['file_degree_id'];
            $oldno = $rowr['case_no'];
            $oldyear = $rowr['year'];
            $olddate = $rowr['opp_date'];
            $oldclientchar = $rowr['client_characteristic'];
            $oldopponentchar = $rowr['opponent_characteristic'];
            
            $file_degree_id = filter_input(INPUT_POST, 'file_degree_id', FILTER_SANITIZE_NUMBER_INT);
            if(isset($file_degree_id) && $file_degree_id != $oldfdi){
                $flag = '1';
                
                $action = $action."<br>تم تغيير الدرجة : من $oldfdi الى $file_degree_id";
            }
            
            $opp_no = filter_input(INPUT_POST, 'opp_no', FILTER_SANITIZE_NUMBER_INT);
            $opp_year = filter_input(INPUT_POST, 'opp_year', FILTER_SANITIZE_NUMBER_INT);
            if((isset($opp_no) && $opp_no != $oldno) || (isset($opp_year) && $opp_year != $oldyear)){
                $flag = '1';
                
                $action = $action."<br>تم تغيير رقم القضية : من $oldno/$oldyear الى $opp_no/$opp_year";
            }
            
            $opp_date = filter_input(INPUT_POST, "opp_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($opp_date) && $opp_date !== $olddate){
                $flag = '1';
                
                $action = $action."<br>تم تغيير التاريخ : من $olddate الى $opp_date";
            }
            
            $client_char = filter_input(INPUT_POST, "client_char", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($client_char) && $client_char !== $oldclientchar){
                $flag = '1';
                
                $action = $action."<br>تم تغيير صفة الموكل : من $oldclientchar الى $client_char";
            }
            
            $opponent_char = filter_input(INPUT_POST, "opponent_char", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($opponent_char) && $opponent_char !== $oldopponentchar){
                $flag = '1';
                
                $action = $action."<br>تم تغيير صفة الخصم : من $oldopponentchar الى $opponent_char";
            }
            
            $timestamp = date("Y-m-d");
            
            $stmt = $conn->prepare("UPDATE oppcase SET fid=?, file_degree_id=?, opp_date=?, case_no=?, year=?, client_characteristic=?, opponent_characteristic=?, created_at=? WHERE id=?");
            $stmt->bind_param("iisiisssi", $fid, $file_degree_id, $opp_date, $opp_no, $opp_year, $client_char, $opponent_char, $timestamp, $id);
            $stmt->execute();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
                $timersubinfo_flag = "القضية المتقابلة رقم : $opp_no / $opp_year في الملف $fid";
                include_once 'timerfunc.php';
            }
            
            header("Location: oppCase.php?fid=$fid");
            exit();
        }
    }
    
    header("Location: oppCase.php?fid=$fid&error=0");
    exit();
?>