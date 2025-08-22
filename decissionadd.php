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
    
    $id = filter_input(INPUT_POST, 'session_id', FILTER_SANITIZE_NUMBER_INT);
    
    if(($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1) && isset($_REQUEST['session_decission']) && $_REQUEST['session_decission'] !== ''){
        $session_id = filter_input(INPUT_POST, 'session_id', FILTER_SANITIZE_NUMBER_INT);
        $session_decission = filter_input(INPUT_POST, "session_decission", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $continue = filter_input(INPUT_POST, 'continue', FILTER_SANITIZE_NUMBER_INT);
        $doc_req = filter_input(INPUT_POST, 'doc_req', FILTER_SANITIZE_NUMBER_INT);
        $session_fid = filter_input(INPUT_POST, 'session_fid', FILTER_SANITIZE_NUMBER_INT);
        
        if($doc_req === '1'){
            $stmtr = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtr->bind_param("i", $session_fid);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $today = new DateTime();
            $future_date = clone $today;
            $future_date->modify('+10 days');
            
            $stmtdeg = $conn->prepare("SELECT * FROM session WHERE session_id=?");
            $stmtdeg->bind_param("i", $session_id);
            $stmtdeg->execute();
            $resultdeg = $stmtdeg->get_result();
            $rowdeg = $resultdeg->fetch_assoc();
            $stmtdeg->close();
            
            $degree = $rowdeg['session_degree'];
            $caseno = $rowdeg['case_num'];
            $year = $rowdeg['year'];
            
            $responsible = $_SESSION['id'];
            $duedate = $future_date->format("Y-m-d");
            $task_type = "كتابة مذكرة";
            $priority = 0;
            $details = "كتابة مذكرة في القضية رقم $degree-$caseno/$year في الملف رقم $session_fid";
            $timestamp = date("Y-m-d");
            $task_status = 0;
            $employee_id = $rowr['flegal_advisor'];
            
            $stmttask = $conn->prepare("INSERT INTO tasks (responsible, employee_id, file_no, task_type, priority, degree, duedate, details, timestamp, task_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmttask->bind_param("iiisissssi", $responsible, $employee_id, $session_fid, $task_type, $priority, $degree, $duedate, $details, $timestamp, $task_status);
            $stmttask->execute();
            $stmttask->close();
        }
        
        if($continue === '1'){
            $stmt = $conn->prepare("UPDATE session SET session_decission=? WHERE session_id=?");
            $stmt->bind_param("si", $session_decission, $session_id);
            $stmt->execute();
            exit;
        } else{
            $zero = 0;
            $empty = '';
            $stmt = $conn->prepare("UPDATE session SET resume_appeal=?, resume_overdue=?, resume_daysno=?, session_decission=? WHERE session_id=?");
            $stmt->bind_param("isisi", $zero, $empty, $zero, $session_decission, $session_id);
            $stmt->execute();
        }
    }
    header("Location: incdec.php?id=$id");
    exit();
?>