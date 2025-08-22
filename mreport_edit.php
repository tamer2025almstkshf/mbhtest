<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['csched_eperm'] == 1){
        if(isset($_REQUEST['report_name']) && $_REQUEST['report_name'] !== '' && isset($_REQUEST['report_details']) && $_REQUEST['report_details'] !== ''){
            $rid = filter_input(INPUT_POST, "rid", FILTER_SANITIZE_NUMBER_INT);
            $stmtr = $conn->prepare("SELECT * FROM meetings_reports WHERE id=?");
            $stmtr->bind_param("i", $rid);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $oldreport_name = $rowr['name'];
            $oldreport_details = $rowr['details'];
            
            $action = "تم تعديل محضر الاجتماع<br>";
            $flag = '0';
            
            $report_name = filter_input(INPUT_POST, "report_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($report_name) && $report_name !== $oldreport_name){
                $action .="<br>تم تغيير اسم المحضر من : $oldreport_name الى $report_name";
                
                $flag = '1';
            }
            $report_details = filter_input(INPUT_POST, "report_details", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($report_details) && $report_details !== $oldreport_details){
                $action .="<br>تم تغيير تفاصيل المحضر من : $oldreport_details الى $report_details";
                
                $flag = '1';
            }
            
            if($flag === '1'){
                include_once 'addlog.php';
                include_once 'timerfunc.php';
            }
            
            $done_by = $_SESSION['id'];
            $timestamp = date("Y-m-d");
            $mid = filter_input(INPUT_POST, "mid", FILTER_SANITIZE_NUMBER_INT);
            
            $stmt = $conn->prepare("UPDATE meetings_reports SET meeting_id=?, name=?, details=?, done_by=?, timestamp=? WHERE id=?");
            $stmt->bind_param("issisi", $mid, $report_name, $report_details, $done_by, $timestamp, $rid);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: meeting_report.php?id=$mid");
    exit();
?>