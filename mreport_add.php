<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['csched_eperm'] == 1){
        if(isset($_REQUEST['report_name']) && $_REQUEST['report_name'] !== '' && isset($_REQUEST['report_details']) && $_REQUEST['report_details'] !== ''){
            $action = "تم كتابة محضر الاجتماع<br>";
            $flag = '0';
            
            $report_name = filter_input(INPUT_POST, "report_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($report_name) && $report_name !== ''){
                $action .="<br>اسم المحضر : $report_name";
                
                $flag = '1';
            }
            $report_details = filter_input(INPUT_POST, "report_details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($report_details) && $report_details !== ''){
                $action .="<br>تفاصيل المحضر : $report_details";
                
                $flag = '1';
            }
            $done_by = $_SESSION['id'];
            $timestamp = date("Y-m-d");
            
            if($flag === '1'){
                include_once 'addlog.php';
                include_once 'timerfunc.php';
            }
            
            $mid = filter_input(INPUT_POST, "mid", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $stmt = $conn->prepare("INSERT INTO meetings_reports (meeting_id, name, details, done_by, timestamp) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issis", $mid, $report_name, $report_details, $done_by, $timestamp);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: meeting_report.php?id=$mid");
    exit();
?>