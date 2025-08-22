<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'safe_output.php';
    
    $fid = filter_input(INPUT_POST, "fid", FILTER_VALIDATE_INT);
    
    $session_id = filter_input(INPUT_POST, "session_id", FILTER_VALIDATE_INT);
    
    if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){
        if(isset($_REQUEST['Hearing_dt']) && $_REQUEST['Hearing_dt'] !== ''){
            $session_id = filter_input(INPUT_POST, "session_id", FILTER_VALIDATE_INT);
            
            $stmtr = $conn->prepare("SELECT * FROM session WHERE session_id=?");
            $stmtr->bind_param("i",$session_id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            
            if(isset($rowr['year'])){
                $year = safe_output($rowr['year']);
            }
            if(isset($rowr['case_num'])){
            $case_num = safe_output($rowr['case_num']);
            }
            if(isset($rowr['session_degree'])){
            $deg = safe_output($rowr['session_degree']);
            }
            
            $action = "تم التعديل على بيانات احد جلسات الملف : <br><br> رقم القضية : $year/$case_num-$deg <br> رقم الملف : $fid";
            $flag = '0';
        
            $session_degree = safe_output($rowr['session_degree']);
            $extended = safe_output($rowr['extended']);
            $jud_session = safe_output($rowr['jud_session']);
            $session_fid = safe_output($rowr['session_fid']);
            
            $session_date = filter_input(INPUT_POST, "session_date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $olddate = '';
            if(isset($rowr['session_date'])){
                $olddate = safe_output($rowr['session_date']);
            }
            if($olddate !== $session_date){
                $action = $action."<br>تم تعديل تاريخ الملف : من $olddate الى $session_date";
                $flag = '1';
            }
            
            $session_decission = filter_input(INPUT_POST, "session_decission", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($session_decission !== ''){
                $action = $action."<br>تم ادخال قرار الجلسة : $session_decission";
                $flag = '1';
            }
            
            $session_details = filter_input(INPUT_POST, "session_decission", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $olddetails = '';
            if(isset($rowr['session_details'])){
                $olddetails = $rowr['session_details'];
            }
            if($olddetails === ''){
                $action = $action."<br>تم ادخال تفاصيل للجلسة : $session_details";
                $flag = '1';
            } else if($olddetails !== '' && $session_details !== $olddetails){
                $action = $action."<br>تم تعديل تفاصيل الجلسة : من $olddetails الى $session_details";
                $flag = '1';
            }
            
            $session_notes = filter_input(INPUT_POST, "session_notes", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $timestamp = date("Y-m-d H:i:s");
            
            $stmt = $conn->prepare("INSERT INTO session (session_fid, session_degree, year, case_num, extended, jud_session, session_date, session_decission, session_details, session_note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isiiiissss",$session_fid,$session_degree,$year,$case_num,$extended,$jud_session,$session_date,$session_decission,$session_details,$session_notes);
            $stmt->execute();
        }
        
        if(isset($_REQUEST['session_notes']) && $_REQUEST['session_notes'] !== ''){
            
            $session_notes = filter_input(INPUT_POST, "session_notes", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $userid = $_SESSION['id'];
            $stmtr2 = $conn->prepare("INSERT INTO session (SELECT * FROM user WHERE id=?");
            $stmtr2->bind_param("i",$userid);
            $stmtr2->execute();
            $resultr2 = $stmtr2->get_result();
            $rowr2 = $resultr2->fetch_assoc();
            $name = $rowr['name'];
            
            $timestamp2 = date("Y-m-d");
            
            $action = $action."<br>تم ادخال ملاحظة للجلسة : $session_notes";
            $flag = '1';
            
            $stmt2 = $conn->prepare("INSERT INTO file_note (file_id, notename, note, doneby, timestamp) VALUES (?, ?, ?, ?, ?)");
            $stmt2->bind_param("issss",$fid,$session_notes,$session_notes,$name,$timestamp2);
            $stmt2->execute();
        }
        
        if($flag === '1'){
            include_once 'addlog.php';
        }
    }
        
    header("Location: hearing_edit.php?sid=$session_id&fid=$fid");
    exit();
?>